<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/catalog', [App\Http\Controllers\ProductController::class, 'index'])->name('catalog');

Route::get('/delivery', fn () => inertia('Delivery'))->name('delivery');
Route::get('/exchange', fn () => inertia('Exchange'))->name('exchange');
Route::get('/contacts', fn () => inertia('Contacts'))->name('contacts');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'create'])->name('checkout');
Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}/success', [App\Http\Controllers\OrderController::class, 'success'])->name('orders.success');

Route::get('dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/admin.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
