<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/players', [AuthController::class, 'store']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
