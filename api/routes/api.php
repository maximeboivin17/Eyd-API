<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\DisabilityController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::resource('demands',DemandController::class)->except([
        'create', 'update'
    ]);
    Route::resource('disabilities', DisabilityController::class)->except([
        'create', 'update'
    ]);
    Route::resource('comments', CommentController::class)->except([
        'create', 'update'
    ]);


    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [UserController::class, 'me']);
    Route::resource('users',UserController::class)->except([
        'create', 'update', 'me'
    ]);
});
