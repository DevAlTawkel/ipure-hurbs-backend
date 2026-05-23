<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'home'])->name('shop.home');
Route::get('/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('shop.product');
