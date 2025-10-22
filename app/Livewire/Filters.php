<?php

namespace App\Livewire;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Filters extends Component
{
    public string $search = '';

    public array $selectedBrands = [];

    public array $selectedCategories = [];

    public int $loadedBrands = 4;

    public int $loadedCategories = 4;

    #[Computed]
    public function brands(): Collection
    {
        return Brand::query()
            ->withCount('products')
            ->orderBy('name')
            ->take($this->loadedBrands)
            ->get();
    }

    public function loadMoreBrands(): void
    {
        $this->loadedBrands += 4;
    }

    public function render(): View
    {
        return view('livewire.filters');
    }
}
