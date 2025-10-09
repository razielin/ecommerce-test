<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function () {
    Route::get('/products', [ProductController::class, 'all']);
    Route::get('/categories', [ProductController::class, 'categories']);
    Route::post('/order', [ProductController::class, 'createNewOrder']);
});
