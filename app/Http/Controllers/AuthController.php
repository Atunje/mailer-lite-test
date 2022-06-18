<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\User;

class AuthController extends Controller
{


    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
        ]);
 
        if ($validator->fails()) {

            return response()->json(['message' => join(" ", $validator->errors()->all()), 'status' => false], 400);

        } else {
 
            // Retrieve the validated input...
            $inputs = $validator->validated();
            $inputs['password'] = Hash::make($request->password);

            if(User::create($inputs)) {
                return response()->json(['message' => 'Registration successful!', 'status' => true], 201);
            }

        }

        return response()->json(['message' => 'There was an error registering!', 'status' => false], 400);

    }
    
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function authenticate(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Authentication attempt failed!', 'status' => false], 401);
        }
        
        $user = User::where('email', $request['email'])->firstOrFail();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        $response_data = [
            'access_token' => $token, 
            'token_type' => 'Bearer'
        ];
        
        return response()->json(['data' => $response_data, 'message' => 'Login successful', 'status'=>true]);
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
    
        return response()->json(['message' => 'Logged out', 'status'=>true]);
    }

}
