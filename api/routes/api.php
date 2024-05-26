<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return response()->json([
        'message' => 'Welcome to our application',
    ]);
});

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout')
        ->middleware('auth:sanctum');
});

Route::put('/register', [RegisterController::class, 'register'])->name('register');
