<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\InventoryController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
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