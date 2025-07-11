<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/list', 'list')->name('list');
        Route::post('/note', 'note')->name('note');
    });

    Route::prefix('sale')->name('sale.')->controller(SaleController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/list', 'list')->name('list');
        Route::get('/removed', 'removed')->name('removed');
        Route::get('/{sale}/delete', 'delete')->name('delete');
        Route::get('/{sale}/restore', 'restore')->name('restore');
    });
});

require __DIR__ . '/auth.php';
