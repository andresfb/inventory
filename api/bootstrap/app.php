<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::bind('user', function ($value) {
                return User::where('id', $value)
                    ->with('categories')
                    ->firstOrFail();
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleWithRedis();
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();