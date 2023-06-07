<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemandController;
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
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});
