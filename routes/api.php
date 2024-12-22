<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PriceController;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('prices/current-hour', [PriceController::class, 'currentHour']);
    Route::get('prices/next-hour', [PriceController::class, 'nextHour']);

    Route::get('/logout', [UserController::class, 'logout']);
});