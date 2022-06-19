<?php

    namespace App\Http\Services;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;
    use Auth;

    class UserService {

        /**
         * Register
         * 
         * Creates new user record
         */
        public function create(array $user_data) 
        {
            $user_data['password'] = Hash::make($user_data['password']);
            return User::create($user_data);
        }

        /**
         * Authenticate
         * 
         * Authenticates user credentials supplied and creates access token
         */
        public function authenticate(Request $request) 
        {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->authResponse(false, 'Authentication attempt failed!');
            }
            
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->authResponse(true, 'Login Successful!', [
                'access_token' => $token, 
                'token_type' => 'Bearer'
            ]);
        }


        /**
         * Converts response into an object
         */
        protected function authResponse(bool $status, string $message="", $data=[]): \stdClass {

            $response = new \stdClass();
            $response->status = $status;
            $response->message = $message;
            $response->data = $data;
    
            return $response;
    
        }

        /**
         * Logout
         * 
         * Deletes all user access tokens
         */
        public function logout() 
        {
            return auth()->user()->tokens()->delete();
        }
    }