<?php

use App\Livewire\Products;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Products::class)
        ->assertStatus(200);
});
