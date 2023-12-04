<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])
        ->name('auth.logout');

    Route::prefix('users')->name('user.')->group(function () {
        Route::post('/update/{user}', [UserController::class, 'update'])
            ->name('update');
        Route::get('/list', [UserController::class, 'list'])
            ->name('list');
        Route::delete('/delete/{user}', [UserController::class, 'delete'])
            ->name('delete');
    });
});

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register');
});
