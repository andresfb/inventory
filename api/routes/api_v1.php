<?php

use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], static function () {

    // Categories
    Route::apiResource('categories', CategoryController::class);

});
