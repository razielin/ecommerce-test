<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth:sanctum', 'verified'])->name('dashboard');

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'all']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
