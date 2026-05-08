<?php

use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\ContentController;
use App\Http\Controllers\Api\V1\FoodController;
use App\Http\Controllers\Api\V1\M3UChannelController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\TenantController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function () {
    // Welcome Route
    Route::get('/', function () {
        return response()->json([
            'message' => 'V1 API',
        ]);
    })->name('welcome');

    // Authentication Routes
    require 'v1/auth.php';

    // Protected Routes (API Key / Bearer Token / Session)
    Route::middleware('auth.api-key')->group(function () {
        // Tenant Info Route
        Route::get('/tenant', [TenantController::class, 'show'])->name('tenant.show');

        // Content Routes
        Route::get('/contents', [ContentController::class, 'contents'])->name('contents');
        Route::get('/content-items', [ContentController::class, 'contentItems'])->name('content-items');
        Route::get('/changelist/contents', [ContentController::class, 'contentsChangeList'])->name('changelist.contents');
        Route::get('/changelist/content-items', [ContentController::class, 'contentItemChangeList'])->name('changelist.content-items');

        // Application Routes
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications');
        Route::get('/changelist/applications', [ApplicationController::class, 'changeList'])->name('changelist.applications');

        // Food Routes
        Route::get('/foods/categories', [FoodController::class, 'categories'])->name('foods.categories');
        Route::get('/foods/categories/{model}', [FoodController::class, 'categoryShow'])->name('foods.categories.show');
        Route::get('/changelist/foods/categories', [FoodController::class, 'categoryChangeList'])->name('changelist.foods.categories');
        Route::get('/foods/items', [FoodController::class, 'items'])->name('foods.items');
        Route::get('/foods/items/{model}', [FoodController::class, 'itemShow'])->name('foods.items.show');
        Route::get('/changelist/foods/items', [FoodController::class, 'itemChangeList'])->name('changelist.foods.items');

        // Room Routes
        Route::get('/rooms/types', [RoomController::class, 'types'])->name('rooms.types');
        Route::get('/rooms/types/{model}', [RoomController::class, 'typeShow'])->name('rooms.types.show');
        Route::get('/rooms/items', [RoomController::class, 'items'])->name('rooms.items');
        Route::get('/rooms/items/{no}', [RoomController::class, 'itemShow'])->name('rooms.items.show');

        // M3U Channel Routes
        Route::get('/m3u-channels', [M3UChannelController::class, 'index'])->name('m3u-channels.index');
    });
});
