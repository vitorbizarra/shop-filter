<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Products extends Component
{
    private const int PER_PAGE = 12;

    public string $search = '';

    public array $brands = [];

    public array $categories = [];

    #[Computed]
    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->paginate(self::PER_PAGE);
    }

    public function render(): View
    {
        return view('livewire.products');
    }
}
