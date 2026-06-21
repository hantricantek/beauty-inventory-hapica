<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Suppliers\Index as SupplierIndex;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('/categories', 'pages::category.index')
    ->name('category.index');

    Route::livewire('/products', 'pages::product.index')
        ->middleware(['auth'])
        ->name('product.index');

    Route::livewire('/suppliers', 'pages::supplier.index')
         ->middleware(['auth'])
         ->name('supplier.index');

    Route::livewire('/inventory', 'pages::inventory.index')
        ->middleware(['auth'])
        ->name('inventory.index');


});


require __DIR__.'/settings.php';