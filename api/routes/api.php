<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\DisabilityController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Routes protégées
Route::group(['middleware' => ['auth:sanctum']], function (){

    // Profil
    Route::get('/me', [UserController::class, 'me']);

    // Demandes d'aides
    Route::resource('demands',DemandController::class)->except([
        'create', 'update'
    ]);

    // Commentaires
    Route::resource('comments', CommentController::class)->except([
        'create', 'update'
    ]);

    // Types d'handicaps
    Route::resource('disabilities', DisabilityController::class)->only([
        'index', 'show'
    ]);

    // Utilisateurs
    Route::resource('users',UserController::class)->except([
        'create', 'update', 'me'
    ]);

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
});
