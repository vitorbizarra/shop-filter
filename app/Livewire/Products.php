<?php

namespace App\Livewire;

use App\Livewire\Traits\HasFilters;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use HasFilters;
    use WithPagination;

    private const int PER_PAGE = 12;

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->when($this->search, fn (Builder $query): Builder => $query->where('name', 'like', "%{$this->search}%"))
            ->when($this->selectedBrands, fn (Builder $query): Builder => $query->whereIn('brand_id', $this->selectedBrands))
            ->when($this->selectedCategories, function (Builder $query): void {
                $query->whereHas('categories', fn (Builder $query): Builder => $query->whereIn('categories.id', $this->selectedCategories));
            })
            ->paginate(self::PER_PAGE);
    }

    #[On('filters::search-updated')]
    public function searchReceived(string $search): void
    {
        $this->search = $search;
        $this->resetPage();
    }

    #[On('filters::brands-updated')]
    public function brandFilterReceived(array $brands): void
    {
        $this->selectedBrands = $brands;
        $this->resetPage();
    }

    #[On('filters::categories-updated')]
    public function categoryFilterReceived(array $categories): void
    {
        $this->selectedCategories = $categories;
        $this->resetPage();
    }

    #[On('filters::reset')]
    public function resetFilters(): void
    {
        $this->reset(['search', 'selectedBrands', 'selectedCategories']);
    }

    public function render(): View
    {
        return view('livewire.products');
    }
}
