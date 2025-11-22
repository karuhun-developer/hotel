<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function () {
    // Welcome Route
    Route::get('/', function () {
        return response()->json([
            'message' => 'V1 API',
        ]);
    })->name('welcome');

    // Authentication Routes
    // require 'v1/auth.php';

    // Tenant Routes
    Route::middleware('auth.api-key')->group(function () {
        // Tenant Info Route
        Route::get('/tenant', [App\Http\Controllers\Api\V1\TenantController::class, 'show'])->name('tenant.show');

        // Content Routes
        Route::get('/contents', [App\Http\Controllers\Api\V1\ContentController::class, 'contents'])->name('contents');
        Route::get('/content-items', [App\Http\Controllers\Api\V1\ContentController::class, 'contentItems'])->name('content-items');
        Route::get('/changelist/contents', [App\Http\Controllers\Api\V1\ContentController::class, 'contentsChangeList'])->name('changelist.contents');
        Route::get('/changelist/content-items', [App\Http\Controllers\Api\V1\ContentController::class, 'contentItemChangeList'])->name('changelist.content-items');

        // Application Routes
        Route::get('/applications', [App\Http\Controllers\Api\V1\ApplicationController::class, 'index'])->name('applications');

        // Food Routes
        Route::get('/foods/categories', [App\Http\Controllers\Api\V1\FoodController::class, 'categories'])->name('foods.categories');
        Route::get('/foods/categories/{model}', [App\Http\Controllers\Api\V1\FoodController::class, 'categoryShow'])->name('foods.categories.show');
        Route::get('/foods/items', [App\Http\Controllers\Api\V1\FoodController::class, 'items'])->name('foods.items');
        Route::get('/foods/items/{model}', [App\Http\Controllers\Api\V1\FoodController::class, 'itemShow'])->name('foods.items.show');

        // Room Routes
        Route::get('/rooms/types', [App\Http\Controllers\Api\V1\RoomController::class, 'types'])->name('rooms.types');
        Route::get('/rooms/types/{model}', [App\Http\Controllers\Api\V1\RoomController::class, 'typeShow'])->name('rooms.types.show');
        Route::get('/rooms/items', [App\Http\Controllers\Api\V1\RoomController::class, 'items'])->name('rooms.items');
        Route::get('/rooms/items/{no}', [App\Http\Controllers\Api\V1\RoomController::class, 'itemShow'])->name('rooms.items.show');

        // M3UChannel Routes
        Route::get('/m3u-channels', [App\Http\Controllers\Api\V1\M3UChannelController::class, 'index'])->name('m3u-channels.index');
    });
});
