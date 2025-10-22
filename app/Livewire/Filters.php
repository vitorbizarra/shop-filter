<?php

namespace App\Livewire;

use App\Livewire\Traits\HasFilters;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Filters extends Component
{
    use HasFilters;

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

    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->take($this->loadedCategories)
            ->get();
    }

    public function updatedSearch(): void
    {
        $this->dispatch('filters::search-updated', search: $this->search);
    }

    public function updatedSelectedBrands(): void
    {
        $this->dispatch('filters::brands-updated', brands: $this->selectedBrands);
    }

    public function updatedSelectedCategories(): void
    {
        $this->dispatch('filters::categories-updated', categories: $this->selectedCategories);
    }

    public function loadMoreBrands(): void
    {
        $this->loadMore('loadedBrands');
    }

    public function loadMoreCategories(): void
    {
        $this->loadMore('loadedCategories');
    }

    private function loadMore(string $property, int $increment = 4): void
    {
        $this->{$property} += $increment;
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'selectedBrands', 'selectedCategories']);
        $this->dispatch('filters::reset');
    }

    public function render(): View
    {
        return view('livewire.filters');
    }
}
