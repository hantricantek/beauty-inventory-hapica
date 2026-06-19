<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Suppliers\Index as SupplierIndex;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ✅ PRODUCTS (kalau masih pakai style lama, biarkan dulu)
    Route::livewire('/products', 'pages::product.index')
        ->middleware(['auth'])
        ->name('product.index');

     Route::livewire('/suppliers', 'pages::supplier.index')
    ->middleware(['auth'])
    ->name('supplier.index');

});

Route::livewire('/products', 'pages::product.index')
    ->middleware(['auth'])
    ->name('product.index');

Route::livewire('/suppliers', 'pages::suppliers.index')
    ->middleware(['auth'])
    ->name('supplier.index');

Route::livewire('/inventory', 'pages::inventory.index')
    ->middleware(['auth'])
    ->name('inventory.index');



Route::resource('stock-in', StockInController::class);

require __DIR__.'/settings.php';