<?php

use App\Http\Controllers\Api\Auth\CustomerAuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

// ─── Currency Detection ───────────────────────────────────────────────────────
Route::get('/currency', [CurrencyController::class, 'detect']);
Route::get('/currency/list', [CurrencyController::class, 'list']);

// ─── Home ────────────────────────────────────────────────────────────────────
Route::get('/home', HomeController::class);
Route::get('/home/home', HomeController::class); // alias for frontend

// ─── Products ────────────────────────────────────────────────────────────────
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/trending', [ProductController::class, 'trending']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::get('/{product}/related', [ProductController::class, 'related']);
    Route::get('/{product}/reviews', [ReviewController::class, 'index']);
});

// ─── Categories ──────────────────────────────────────────────────────────────
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::get('/{category}/products', [CategoryController::class, 'products']);
});

// ─── Brands ──────────────────────────────────────────────────────────────────
Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::get('/directory', [BrandController::class, 'directory']);
    Route::get('/{brand}', [BrandController::class, 'show']);
    Route::get('/{brand}/products', [BrandController::class, 'products']);
});

// ─── Cart (guest + auth) ──────────────────────────────────────────────────────
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::patch('/items/{item}', [CartController::class, 'updateItem']);
    Route::delete('/items/{item}', [CartController::class, 'removeItem']);
    Route::delete('/', [CartController::class, 'clear']);
});

// ─── Customer Auth ────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/login', [CustomerAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
        Route::get('/profile', [CustomerAuthController::class, 'profile']);
        Route::patch('/profile', [CustomerAuthController::class, 'updateProfile']);
        Route::post('/addresses', [CustomerAuthController::class, 'storeAddress']);
        Route::delete('/addresses/{id}', [CustomerAuthController::class, 'deleteAddress']);
    });
});

// ─── Checkout ────────────────────────────────────────────────────────────────
Route::prefix('checkout')->group(function () {
    Route::post('/initiate', [CheckoutController::class, 'initiate']);
    Route::post('/confirm', [CheckoutController::class, 'confirm']);
});

// ─── Orders (authenticated customers only) ───────────────────────────────────
Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{orderNumber}', [OrderController::class, 'show']);
    Route::post('/{orderNumber}/cancel', [OrderController::class, 'cancel']);
});

// ─── Reviews (post requires auth, get is public — handled above) ─────────────
Route::middleware('auth:sanctum')->post('/products/{product}/reviews', [ReviewController::class, 'store']);

// ─── Wishlist (authenticated customers only) ─────────────────────────────────
Route::middleware('auth:sanctum')->prefix('wishlist')->group(function () {
    Route::get('/', [WishlistController::class, 'index']);
    Route::get('/count', [WishlistController::class, 'count']);
    Route::post('/', [WishlistController::class, 'store']);
    Route::delete('/', [WishlistController::class, 'destroy']);
});

// ─── Coupons ──────────────────────────────────────────────────────────────────
Route::prefix('coupons')->group(function () {
    Route::post('/validate', [CouponController::class, 'validate']);
    Route::get('/{code}', [CouponController::class, 'show']);
});

// ─── Inventory Management (public endpoints) ──────────────────────────────────
Route::prefix('inventory')->group(function () {
    Route::get('/report', [InventoryController::class, 'report']);
    Route::get('/low-stock', [InventoryController::class, 'lowStock']);
    Route::get('/out-of-stock', [InventoryController::class, 'outOfStock']);
    Route::get('/products/{product}/movements', [InventoryController::class, 'movements']);
});

// ─── Stripe Webhook (no CSRF / auth) ─────────────────────────────────────────
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware(['api']);
