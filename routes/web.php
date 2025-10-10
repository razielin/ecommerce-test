<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth:sanctum', 'verified'])->name('dashboard');

Route::get('products', function () {
    return Inertia::render('Products');
})->middleware(['auth:sanctum', 'verified'])->name('Products');

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'all']);
    Route::post('/product/edit', [ProductController::class, 'editProduct']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
