<?php

use App\Http\Controllers\DemandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Public routes


//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::resource('demands',DemandController::class)->except([
        'create', 'update'
    ]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
