<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Route::middleware('auth:sanctum')->group(function () {
        // routes hotels
        Route::prefix('hotels')->group(function () {
            Route::get('/', [HotelController::class, 'index']); 
            Route::post('/', [HotelController::class, 'store']); 
            Route::get('{hotel}', [HotelController::class, 'show']); 
            Route::put('{hotel}', [HotelController::class, 'update']); 
            Route::delete('{hotel}', [HotelController::class, 'destroy']); 
        });

        // routes rooms
        Route::prefix('hotels/{hotel}/rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index']); 
            Route::post('/', [RoomController::class, 'store']); 
            Route::get('/{room}', [RoomController::class, 'show']); 
            Route::put('/{room}', [RoomController::class, 'update']); 
            Route::delete('/{room}', [RoomController::class, 'destroy']); 
        });
    // });
});