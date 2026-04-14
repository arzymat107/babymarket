<?php

use App\Http\Controllers\Admin\AgeGroupController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\StatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [StatsController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);

    // Brands
    Route::post('categories/{category}/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::patch('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Sizes
    Route::post('categories/{category}/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::patch('sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
    Route::delete('sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

    // Age groups
    Route::post('categories/{category}/age-groups', [AgeGroupController::class, 'store'])->name('age_groups.store');
    Route::patch('age-groups/{ageGroup}', [AgeGroupController::class, 'update'])->name('age_groups.update');
    Route::delete('age-groups/{ageGroup}', [AgeGroupController::class, 'destroy'])->name('age_groups.destroy');
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('orders/{order}/paid', [OrderController::class, 'updatePaid'])->name('orders.paid');
});