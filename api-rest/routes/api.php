<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\LoginController;
use \App\Http\Controllers\Api\V1\Auth\RegisterController;
use \App\Http\Controllers\Api\V1\UserController;
use \App\Http\Controllers\Api\V1\GameController;
use \App\Http\Controllers\Api\V1\RankingController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/players', [RegisterController::class, 'register']);
    Route::put('players/{id}', [UserController::class, 'changePlayerNickname'])->middleware('auth:api');
    Route::post('players/{id}/games', [GameController::class, 'play'])->middleware('auth:api');
    Route::get('players', [UserController::class, 'listAllPlayersWithStats'])->middleware('auth:api');
    Route::get('players/{id}/games', [GameController::class, 'listPlayerGamesWithStats'])->middleware('auth:api');
    Route::get('players/ranking', [RankingController::class, 'getRanking']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
