<?php

use Illuminate\Support\Facades\Route;

// Logs
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs')->middleware('auth', 'role:superadmin');
