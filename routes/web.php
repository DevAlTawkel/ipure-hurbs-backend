<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'home'])->name('shop.home');
Route::get('/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/products/{product:slug}', [ShopController::class, 'show'])->name('shop.product');
Route::get('/create-admin', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'ancy@altawkelcenter.com'],
        [
            'name' => 'Admin',
            'password' => 'Admin@123',
        ]
    );

    return 'Admin created';
});
