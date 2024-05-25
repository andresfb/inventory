<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.')
    ->middleware('throttle:api')
    ->group(function () {

        Route::get('/', static function () {
            return response()->json([
                'message' => 'Welcome to our application',
            ]);
        });

        Route::controller(AuthController::class)->group(function () {
            Route::post('/login', 'login')->name('login');
            Route::post('/register', 'register')->name('register');
        });

        Route::get('/user', static function (Request $request) {
            return $request->user();
        })
            ->name('user.show')
            ->middleware('auth:sanctum');

    });
