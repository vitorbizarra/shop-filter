<?php

use App\Livewire;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\Products::class)->name('products');
