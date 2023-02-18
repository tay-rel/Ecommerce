<?php

use App\Http\Livewire\Admin\ShowProducts;
use Illuminate\Support\Facades\Route;


Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('products/{product}/edit', function () {})->name('admin.products.edit');
Route::get('products/create', function () {})->name('admin.products.create');
