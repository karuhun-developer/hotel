<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'api.v1.',
    'middleware' => ['api', 'auth.api-key'],
], function () {
    Route::get('/tv', [App\Http\Controllers\Api\V1\TvController::class, 'get'])->name('tv');
    Route::get('/hotel', [App\Http\Controllers\Api\V1\HotelController::class, 'get'])->name('hotel');
    Route::get('/facility', [App\Http\Controllers\Api\V1\HotelController::class, 'facility'])->name('facility');
    Route::get('/policy', [App\Http\Controllers\Api\V1\PolicyController::class, 'get'])->name('policy');
    Route::get('/promo', [App\Http\Controllers\Api\V1\PromoController::class, 'get'])->name('promo');
    Route::get('/food-category', [App\Http\Controllers\Api\V1\FoodController::class, 'category'])->name('food-category');
    Route::get('/food/{category}', [App\Http\Controllers\Api\V1\FoodController::class, 'getFoodByCategory'])->name('food-by-category');
    Route::get('/food', [App\Http\Controllers\Api\V1\FoodController::class, 'get'])->name('food');
    Route::get('/around', [App\Http\Controllers\Api\V1\AroundController::class, 'get'])->name('around');
    Route::get('/event', [App\Http\Controllers\Api\V1\EventController::class, 'get'])->name('event');
    Route::get('/room', [App\Http\Controllers\Api\V1\RoomController::class, 'get'])->name('room');
    Route::get('/room-type', [App\Http\Controllers\Api\V1\RoomController::class, 'type'])->name('room.type');
    Route::get('/room-type/{id}', [App\Http\Controllers\Api\V1\RoomController::class, 'typeDetail'])->name('room.type.detail');
    Route::get('/room/{id}', [App\Http\Controllers\Api\V1\RoomController::class, 'detail'])->name('room.detail');
    Route::get('/doctor-category', [App\Http\Controllers\Api\V1\DoctorController::class, 'category'])->name('doctor-category');
    Route::get('/doctor/{category}', [App\Http\Controllers\Api\V1\DoctorController::class, 'getDoctorByCategory'])->name('doctor');
    Route::get('/doctor/{category}', [App\Http\Controllers\Api\V1\DoctorController::class, 'getDoctorByCategory'])->name('doctor');
    // Route::get('/wifi', [App\Http\Controllers\Api\V1\WifiController::class, 'get'])->name('wifi');

    //change list
    Route::get('/changelist/food-category', [App\Http\Controllers\Api\V1\FoodController::class, 'getFoodCategoryChangeList'])->name('changelist.food-category');

    // Features
    Route::get('/features', [App\Http\Controllers\Api\V1\FeatureController::class, 'get'])->name('features');
    Route::get('/feature-item/{id}', [App\Http\Controllers\Api\V1\FeatureController::class, 'getFeatureItems'])->name('feature-item');

    //Application
    Route::get('/applications', [App\Http\Controllers\Api\V1\ApplicationController::class, 'get'])->name('applications');
});
