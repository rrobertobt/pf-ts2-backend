<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\NichesController;
use App\Http\Controllers\OccupantsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware([IsUserAuth::class])->group(function () {
  Route::controller(AuthController::class)->group(function () {
    Route::get('/me', 'myProfile');
    Route::post('/logout', 'logout');
  });

  // Genders routes
  Route::controller(GenderController::class)->group(function () {
    Route::get('/genders', 'index');
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
    Route::put('/users/password', 'updatePassword');
    Route::delete('/users/{user_id}', 'destroy');
  });

  // Niches routes
  Route::controller(NichesController::class)->group(function () {
    Route::get('/niches', 'index');
    Route::post('/niches', 'store');
    Route::get('/niches/states', 'states');
    Route::get('/niches/types', 'types');
  });

  // Occupants routes
  Route::controller(OccupantsController::class)->group(function () {
    Route::get('/occupants', 'index');
    Route::post('/occupants', 'store');
    Route::get('/occupants/{occupant_id}', 'show');
    Route::delete('/occupants/{occupant_id}', 'destroy');
  });

  // Contract routes
  Route::controller(ContractController::class)->group(function () {
    Route::get('/contracts', 'index');
    Route::post('/contracts', 'store');
    Route::get('/contracts/states', 'states');
    Route::get('/contracts/{contract_id}', 'show');
    Route::put('/contracts/{contract_id}', 'update');
    Route::delete('/contracts/{contract_id}', 'destroy');
  });

  // Payment routes
  Route::controller(PaymentController::class)->group(function () {
    Route::get('/payments', 'index');
    Route::post('/payments', 'store');
    Route::get('/payments/{payment_id}', 'show');
    Route::put('/payments/{payment_id}', 'update');
    Route::delete('/payments/{payment_id}', 'destroy');
  });
});
