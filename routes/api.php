<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function() {
    return response()->json(['message' => "Welcome to Mailer Lite Test API", 'version' => '1.0.0']);
});

Route::group(['prefix'=>'auth'], function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::middleware('auth:sanctum')->get('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $data = ['user'=>$request->user()];
    return response()->json(['data' => $data, 'message' => 'User profile successfully retrieved', 'status'=> true]);
});

Route::group(['prefix'=>'fields', 'middleware' => ['auth:sanctum']], function(){
    Route::get('/', [FieldController::class, 'show']);
    Route::post('create', [FieldController::class, 'create']);
    Route::post('/{field}/update', [FieldController::class, 'update']);
});

Route::group(['prefix'=>'subscribers', 'middleware' => ['auth:sanctum']], function(){
    Route::post('/', [SubscriberController::class, 'show']);
    Route::get('/', [SubscriberController::class, 'show']);
    Route::get('/{subscriber}', [SubscriberController::class, 'view']);
    Route::post('create', [SubscriberController::class, 'create']);
    Route::put('/{subscriber}/update', [SubscriberController::class, 'update']);
    Route::delete('/{subscriber}/delete', [SubscriberController::class, 'delete']);
    Route::post('/change-state', [SubscriberController::class, 'changeState']);
    Route::post('/bulk-delete', [SubscriberController::class, 'bulkDelete']);
});
