<?php

use App\Livewire\Filters;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

use function Pest\Livewire\livewire;

it('renders successfully', function () {
    livewire(Filters::class)
        ->assertSeeHtmlInOrder([
            __('Search Products'),
            __('Brands'),
            __('Categories'),
        ])
        ->assertOk();
});

describe('search', function () {
    it('can update search property', function () {
        livewire(Filters::class)
            ->set('search', 'laptop')
            ->assertSet('search', 'laptop');
    });

    it('dispatches event when search is updated', function () {
        livewire(Filters::class)
            ->set('search', 'laptop')
            ->assertDispatched('filters::search-updated', ['search' => 'laptop']);
    });

    it('dispatches event with empty string when search is cleared', function () {
        livewire(Filters::class)
            ->set('search', 'laptop')
            ->set('search', '')
            ->assertDispatched('filters::search-updated', ['search' => '']);
    });
});

describe('brand filtering', function () {
    it('can select a brand', function () {
        $brand = Brand::factory()->create(['name' => 'Apple']);

        livewire(Filters::class)
            ->set('selectedBrands', [$brand->id])
            ->assertSet('selectedBrands', [$brand->id]);
    });

    it('can select multiple brands', function () {
        $brands = Brand::factory(3)->create();

        $brandIds = $brands->pluck('id')->toArray();

        livewire(Filters::class)
            ->set('selectedBrands', $brandIds)
            ->assertSet('selectedBrands', $brandIds);
    });

    it('can deselect a brand', function () {
        [$firstBrand, $secondBrand] = Brand::factory()->count(2)->create();

        livewire(Filters::class)
            ->set('selectedBrands', [$firstBrand->id, $secondBrand->id])
            ->set('selectedBrands', [$firstBrand->id])
            ->assertSet('selectedBrands', [$firstBrand->id]);
    });

    it('can clear all selected brands', function () {
        $brands = Brand::factory(3)->create();

        livewire(Filters::class)
            ->set('selectedBrands', $brands->pluck('id')->toArray())
            ->set('selectedBrands', [])
            ->assertSet('selectedBrands', []);
    });
});

describe('brand loading', function () {
    it('loads initial 4 brands by default', function () {
        Brand::factory(10)->create();

        livewire(Filters::class)
            ->assertCount('brands', 4);
    });

    it('can load more brands', function () {
        Brand::factory(8)->create();

        livewire(Filters::class)
            ->assertSet('loadedBrands', 4)
            ->assertCount('brands', 4)
            ->call('loadMoreBrands')
            ->assertSet('loadedBrands', 8)
            ->assertCount('brands', 8);
    });

    it('does not fail when loading more brands than exist', function () {
        Brand::factory(5)->create();

        livewire(Filters::class)
            ->call('loadMoreBrands')
            ->assertCount('brands', 5)
            ->assertOk()
            ->call('loadMoreBrands')
            ->assertCount('brands', 5)
            ->assertOk();
    });
});

describe('brand ordering and counting', function () {
    it('orders brands alphabetically by name', function () {
        Brand::factory(3)
            ->sequence(
                ['name' => 'Zebra'],
                ['name' => 'Apple'],
                ['name' => 'Microsoft'],
            )
            ->create();

        livewire(Filters::class)
            ->assertSet('brands.0.name', 'Apple')
            ->assertSet('brands.1.name', 'Microsoft')
            ->assertSet('brands.2.name', 'Zebra')
            ->assertSeeHtmlInOrder(['Apple', 'Microsoft', 'Zebra']);
    });

    it('shows correct product counts for multiple brands', function () {
        [$firstBrand, $secondBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        Product::factory(3)->for($firstBrand)->create();
        Product::factory(7)->for($secondBrand)->create();

        livewire(Filters::class)
            ->assertSet('brands.0.products_count', 3)
            ->assertSet('brands.1.products_count', 7)
            ->assertSeeHtmlInOrder(['Apple', 'Samsung']);
    });

    it('shows zero count for brands without products', function () {
        Brand::factory()->create(['name' => 'EmptyBrand']);

        livewire(Filters::class)
            ->assertSet('brands.0.products_count', 0);
    });
});

describe('category filtering', function () {
    it('can select a category', function () {
        $category = Category::factory()->create(['name' => 'Electronics']);

        livewire(Filters::class)
            ->set('selectedCategories', [$category->id])
            ->assertSet('selectedCategories', [$category->id]);
    });

    it('can select multiple categories', function () {
        $categories = Category::factory(3)->create();
        $categoryIds = $categories->pluck('id')->toArray();

        livewire(Filters::class)
            ->set('selectedCategories', $categoryIds)
            ->assertSet('selectedCategories', $categoryIds);
    });

    it('can deselect a category', function () {
        [$firstCategory, $secondCategory] = Category::factory(2)->create();

        livewire(Filters::class)
            ->set('selectedCategories', [$firstCategory->id, $secondCategory->id])
            ->set('selectedCategories', [$firstCategory->id])
            ->assertSet('selectedCategories', [$firstCategory->id]);
    });

    it('can clear all selected categories', function () {
        $categories = Category::factory(3)->create();

        livewire(Filters::class)
            ->set('selectedCategories', $categories->pluck('id')->toArray())
            ->set('selectedCategories', [])
            ->assertSet('selectedCategories', []);
    });
});

describe('category loading', function () {
    it('loads initial 4 categories by default', function () {
        Category::factory(10)->create();

        livewire(Filters::class)
            ->assertCount('categories', 4);
    });

    it('can load more categories', function () {
        Category::factory(8)->create();

        livewire(Filters::class)
            ->assertSet('loadedCategories', 4)
            ->assertCount('categories', 4)
            ->call('loadMoreCategories')
            ->assertSet('loadedCategories', 8)
            ->assertCount('categories', 8);
    });

    it('does not fail when loading more categories than exist', function () {
        Category::factory(5)->create();

        livewire(Filters::class)
            ->call('loadMoreCategories')
            ->assertCount('categories', 5)
            ->assertOk()
            ->call('loadMoreCategories')
            ->assertCount('categories', 5)
            ->assertOk();
    });
});

describe('category ordering and counting', function () {
    it('orders categories alphabetically by name', function () {
        Category::factory(3)
            ->sequence(
                ['name' => 'Toys'],
                ['name' => 'Electronics'],
                ['name' => 'Home & Garden'],
            )
            ->create();

        livewire(Filters::class)
            ->assertSet('categories.0.name', 'Electronics')
            ->assertSet('categories.1.name', 'Home & Garden')
            ->assertSet('categories.2.name', 'Toys')
            ->assertSeeHtmlInOrder(['Electronics', 'Home & Garden', 'Toys']);
    });

    it('shows correct product counts for multiple categories', function () {
        [$firstCategory, $secondCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Toys']
            )
            ->create();

        $firstCategory->products()->attach(Product::factory(3)->create());

        $secondCategory->products()->attach(Product::factory(7)->create());

        livewire(Filters::class)
            ->assertSet('categories.0.products_count', 3)
            ->assertSet('categories.1.products_count', 7)
            ->assertSeeHtmlInOrder(['Electronics', 'Toys']);
    });

    it('shows zero count for categories without products', function () {
        Category::factory()->create(['name' => 'EmptyCategory']);

        livewire(Filters::class)
            ->assertSet('categories.0.products_count', 0);
    });
});
