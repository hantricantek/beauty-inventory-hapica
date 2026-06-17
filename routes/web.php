<?php

use Illuminate\Support\Facades\Route;

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

require __DIR__.'/settings.php';