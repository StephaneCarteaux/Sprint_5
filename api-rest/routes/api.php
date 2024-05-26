<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\RegisteredUserController;
use \App\Http\Controllers\Api\V1\Auth\LoginController;
use \App\Http\Controllers\Api\V1\GameController;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'login']); // Login
    Route::post('/players', [RegisteredUserController::class, 'store']); // Create new player
    Route::put('players/{id}', [RegisteredUserController::class, 'update'])->middleware('auth:api'); // Change player name
    Route::post('players/{id}/games', [GameController::class, 'play'])->middleware('auth:api'); // Player throws dices
    Route::get('players', [RegisteredUserController::class, 'index'])->middleware('auth:api'); // Get all players whith stats (Admin only)
    Route::get('players/{id}/games', [GameController::class, 'index'])->middleware('auth:api'); // Get all games of a player
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
