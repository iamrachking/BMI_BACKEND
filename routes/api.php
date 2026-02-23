<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Ecommerce\CartController;
use App\Http\Controllers\Api\Ecommerce\CategoryController;
use App\Http\Controllers\Api\Ecommerce\OrderController;
use App\Http\Controllers\Api\Ecommerce\ProductController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

// Authentification de l'app mobile e-commerce clients uniquement
Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot-password', [LoginController::class, 'forgotPassword']);
Route::post('/password/reset', [LoginController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'user']);
    Route::patch('/user', [LoginController::class, 'update']);
    Route::post('/user/photo', [LoginController::class, 'updatePhoto']);
    Route::patch('/user/password', [LoginController::class, 'changePassword']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::get('/cart', [CartController::class, 'show']);
    Route::delete('/cart', [CartController::class, 'clear']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{order}/payment', [OrderController::class, 'initiatePayment']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
});

// Callback FedaPay après paiement (redirection navigateur / WebView)
Route::get('/orders/{order}/payment/callback', [OrderController::class, 'paymentCallback']);

// Webhooks (FedaPay = notification transaction.approved ; payment = générique back-office)
Route::get('/webhooks/fedapay', [WebhookController::class, 'fedapayVerify']);
Route::post('/webhooks/fedapay', [WebhookController::class, 'fedapay']);
Route::post('/webhooks/payment', [WebhookController::class, 'payment']);
