<?php

use App\Livewire\Filters;
use App\Models\Brand;
use App\Models\Product;

use function Pest\Livewire\livewire;

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
