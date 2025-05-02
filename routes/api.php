<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/me', 'myProfile');
        Route::post('/logout', 'logout');
    });

    // Roles routes
    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles', 'index');
    });

    // Users routes
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index');
        Route::get('/users/{user_id}', 'show');
        Route::post('/users', 'store');
        Route::delete('/users/{user_id}', 'destroy');
    });
});