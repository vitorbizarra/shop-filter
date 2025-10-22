<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\Url;

trait HasFilters
{
    #[Url(as: 'q', keep: true, except: '')]
    public string $search = '';

    #[Url(as: 'brands', keep: true)]
    public array $selectedBrands = [];

    #[Url(as: 'categories', keep: true)]
    public array $selectedCategories = [];
}
