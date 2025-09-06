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
    Route::get('/changelist/food', [App\Http\Controllers\Api\V1\FoodController::class, 'getFoodChangeList'])->name('changelist.food');
    Route::get('/changelist/applications', [App\Http\Controllers\Api\V1\ApplicationController::class, 'changelist'])->name('changelist.applications');
    Route::get('/changelist/contents', [App\Http\Controllers\Api\V1\ContentController::class, 'contentsChangeList'])->name('changelist.features');
    Route::get('/changelist/content-items', [App\Http\Controllers\Api\V1\ContentController::class, 'contentItemChangeList'])->name('changelist.feature-items');
    
    Route::get('/contents', [App\Http\Controllers\Api\V1\ContentController::class, 'contents'])->name('contents');
    Route::get('/content-items', [App\Http\Controllers\Api\V1\ContentController::class, 'contentItems'])->name('content-items');

    //Application
    Route::get('/applications', [App\Http\Controllers\Api\V1\ApplicationController::class, 'get'])->name('applications');
});
