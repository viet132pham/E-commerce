<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\CategoryAdminController;
use App\Http\Controllers\Api\Admin\ProductAdminController;
use App\Http\Controllers\Api\Admin\ProductVariantAdminController;
use App\Http\Controllers\Api\Admin\OrderAdminController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product:slug}', [ProductController::class, 'show']);

Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem']);

Route::post('/checkout', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{order}', [OrderController::class, 'show']);

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/categories', [CategoryAdminController::class, 'index']);
    Route::post('/categories', [CategoryAdminController::class, 'store']);
    Route::get('/categories/{category}', [CategoryAdminController::class, 'show']);
    Route::patch('/categories/{category}', [CategoryAdminController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryAdminController::class, 'destroy']);

    Route::get('/products', [ProductAdminController::class, 'index']);
    Route::post('/products', [ProductAdminController::class, 'store']);
    Route::get('/products/{product}', [ProductAdminController::class, 'show']);
    Route::patch('/products/{product}', [ProductAdminController::class, 'update']);
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy']);

    Route::post('/products/{product}/variants', [ProductVariantAdminController::class, 'store']);
    Route::patch('/variants/{variant}', [ProductVariantAdminController::class, 'update']);
    Route::delete('/variants/{variant}', [ProductVariantAdminController::class, 'destroy']);

    Route::get('/orders', [OrderAdminController::class, 'index']);
    Route::get('/orders/{order}', [OrderAdminController::class, 'show']);
    Route::patch('/orders/{order}', [OrderAdminController::class, 'update']);
});
