<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Middleware\InitializeCart;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('categories', [CategoryController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(InitializeCart::class)->group(function () {
    Route::post('cart/add', [CartController::class, 'addItem']);
    Route::delete('cart/remove/{product_id}', [CartController::class, 'removeItem']);
    Route::put('cart/update/{product_id}', [CartController::class, 'updateItem']);
    Route::get('cart', [CartController::class, 'getCart']);
    Route::post('cart/clear', [CartController::class, 'clearCart']);
});
