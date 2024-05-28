<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\LoginController;
use \App\Http\Controllers\Api\V1\Auth\RegisterController;
use \App\Http\Controllers\Api\V1\UserController;
use \App\Http\Controllers\Api\V1\GameController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'login']); // Login
    Route::post('/players', [RegisterController::class, 'register']); // Register new player
    Route::put('players/{id}', [UserController::class, 'update'])->middleware('auth:api'); // Change player name
    Route::post('players/{id}/games', [GameController::class, 'play'])->middleware('auth:api'); // Player throws dices
    Route::get('players', [UserController::class, 'index'])->middleware('auth:api'); // Get all players whith stats (Admin only)
    Route::get('players/{id}/games', [GameController::class, 'listPlayerGamesWithStats'])->middleware('auth:api');
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
