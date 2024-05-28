<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], static function () {

    // Categories
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::get('/categories/{categoryId}', 'show')->name('categories.show');
    });

    // Users
    Route::controller(UserController::class)->group(function () {
       Route::get('/users/{userId}', 'show')->name('user.show');
       Route::put('/users/{userId}', 'update')->name('user.update');
    });

    // Properties
    Route::controller(PropertyController::class)->group(function () {
        Route::get('/properties/{categoryId}', 'index')->name('properties.index');
        Route::get('/properties/{categoryId}/{propertyId}', 'show')->name('properties.show');
    });

    // Items
    Route::controller(ItemController::class)->group(function () {
       Route::get('/items', 'index')->name('items.index');
       Route::get('/items/{itemId}', 'show')->name('items.show');
       Route::post('/items/{itemId}', 'store')->name('items.store');
       Route::put('/items/{itemId}', 'update')->name('items.update');
       Route::delete('/items/{itemId}', 'destroy')->name('items.destroy');
    });
});
