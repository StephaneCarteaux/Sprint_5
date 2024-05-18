<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\Auth\RegisterController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/players', RegisterController::class);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
