<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\UserService;

class AuthController extends Controller
{

    public function __construct(private readonly UserService $userService){}

    public function register(RegisterRequest $request) 
    {
        $inputs = $request->validated();

        if($this->userService->create($inputs)) 
            return $this->successResponseNoData('Registration successful!', 201);
        
        return $this->failureResponse('There was an error registering!');
    }
    
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function authenticate(Request $request)
    {
        $result = $this->userService->authenticate($request);
        if($result->status) return $this->successResponse($result->message, $result->data);
        
        return $this->failureResponse($result->message, 401);
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout()
    {
        if($this->userService->logout()) 
            return $this->successResponse('Logged out');

        return $this->failureResponse('There was an error logging you out!');
    }

}
