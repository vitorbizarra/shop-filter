<?php

use App\Livewire\Products;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

use function Pest\Livewire\livewire;

describe('search filtering', function () {
    it('filters products by name when search event is received', function () {
        $brand = Brand::factory()->create();

        [$matchingProduct, $nonMatchingProduct] = Product::factory(2)
            ->for($brand)
            ->sequence(
                ['name' => 'MacBook Pro'],
                ['name' => 'Dell XPS']
            )
            ->create();

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'MacBook')
            ->assertSee($matchingProduct->name)
            ->assertDontSee($nonMatchingProduct->name);
    });

    it('performs case-insensitive partial search', function () {
        $brand = Brand::factory()->create();

        [$firstProduct, $secondProduct] = Product::factory(2)
            ->for($brand)
            ->sequence(
                ['name' => 'iPhone 15 Pro'],
                ['name' => 'Samsung Galaxy']
            )
            ->create();

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'iphone')
            ->assertSee($firstProduct->name)
            ->assertDontSee($secondProduct->name);
    });

    it('shows all products when search is empty', function () {
        $brand = Brand::factory()->create();

        [$firstProduct, $secondProduct] = Product::factory(2)
            ->for($brand)
            ->sequence(
                ['name' => 'Product 1'],
                ['name' => 'Product 2']
            )
            ->create();

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: '')
            ->assertSee($firstProduct->name)
            ->assertSee($secondProduct->name);
    });

    it('shows no products when search matches nothing', function () {
        $brand = Brand::factory()->create();

        Product::factory()
            ->for($brand)
            ->create(['name' => 'Product 1']);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'NonExistentProduct')
            ->assertDontSee('Product 1');
    });

    it('resets to first page when search is updated', function () {
        $brand = Brand::factory()->create();

        Product::factory(25)->for($brand)->create();

        livewire(Products::class)
            ->call('gotoPage', 2)
            ->dispatch('filters::search-updated', search: 'Product')
            ->assertSet('paginators.page', 1);
    });
});

describe('brand filtering', function () {
    it('filters products by single brand', function () {
        [$appleBrand, $samsungBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        $appleProduct = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'iPhone']);

        $samsungProduct = Product::factory()
            ->for($samsungBrand)
            ->create(['name' => 'Galaxy']);

        livewire(Products::class)
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->assertSee($appleProduct->name)
            ->assertDontSee($samsungProduct->name);
    });

    it('filters products by multiple brands', function () {
        [$appleBrand, $samsungBrand, $dellBrand] = Brand::factory(3)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung'],
                ['name' => 'Dell']
            )
            ->create();

        $appleProduct = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'iPhone']);

        $samsungProduct = Product::factory()
            ->for($samsungBrand)
            ->create(['name' => 'Galaxy']);

        $dellProduct = Product::factory()
            ->for($dellBrand)
            ->create(['name' => 'XPS']);

        livewire(Products::class)
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id, $samsungBrand->id])
            ->assertSee($appleProduct->name)
            ->assertSee($samsungProduct->name)
            ->assertDontSee($dellProduct->name);
    });

    it('shows all products when brand filter is empty', function () {
        [$firstBrand, $secondBrand] = Brand::factory(2)->create();

        $firstProduct = Product::factory()
            ->for($firstBrand)
            ->create(['name' => 'Product 1']);

        $secondProduct = Product::factory()
            ->for($secondBrand)
            ->create(['name' => 'Product 2']);

        livewire(Products::class)
            ->dispatch('filters::brands-updated', brands: [])
            ->assertSee($firstProduct->name)
            ->assertSee($secondProduct->name);
    });

    it('resets to first page when brand filter is updated', function () {
        $brand = Brand::factory()->create();

        Product::factory(25)->for($brand)->create();

        livewire(Products::class)
            ->call('gotoPage', 2)
            ->dispatch('filters::brands-updated', brands: [$brand->id])
            ->assertSet('paginators.page', 1);
    });
});

describe('category filtering', function () {
    it('filters products by single category', function () {
        [$electronicsCategory, $toysCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Toys']
            )
            ->create();

        $brand = Brand::factory()->create();

        $electronicsProduct = Product::factory()
            ->for($brand)
            ->create(['name' => 'Laptop']);

        $electronicsProduct->categories()->attach($electronicsCategory);

        $toyProduct = Product::factory()
            ->for($brand)
            ->create(['name' => 'Action Figure']);

        $toyProduct->categories()->attach($toysCategory);

        livewire(Products::class)
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee($electronicsProduct->name)
            ->assertDontSee($toyProduct->name);
    });

    it('filters products by multiple categories', function () {
        [$electronicsCategory, $toysCategory, $booksCategory] = Category::factory(3)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Toys'],
                ['name' => 'Books']
            )
            ->create();

        $brand = Brand::factory()->create();

        $electronicsProduct = Product::factory()
            ->for($brand)
            ->create(['name' => 'Laptop']);
        $electronicsProduct->categories()->attach($electronicsCategory);

        $toyProduct = Product::factory()
            ->for($brand)
            ->create(['name' => 'Action Figure']);
        $toyProduct->categories()->attach($toysCategory);

        $bookProduct = Product::factory()
            ->for($brand)
            ->create(['name' => 'Novel']);
        $bookProduct->categories()->attach($booksCategory);

        livewire(Products::class)
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id, $toysCategory->id])
            ->assertSee($electronicsProduct->name)
            ->assertSee($toyProduct->name)
            ->assertDontSee($bookProduct->name);
    });

    it('shows products that belong to multiple categories when filtering by one', function () {
        [$electronicsCategory, $gamingCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Gaming']
            )
            ->create();

        $brand = Brand::factory()->create();

        $gamingLaptop = Product::factory()
            ->for($brand)
            ->create(['name' => 'Gaming Laptop']);
        $gamingLaptop->categories()->attach([$electronicsCategory->id, $gamingCategory->id]);

        livewire(Products::class)
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee($gamingLaptop->name);
    });

    it('shows all products when category filter is empty', function () {
        [$firstCategory, $secondCategory] = Category::factory(2)->create();
        $brand = Brand::factory()->create();

        $product1 = Product::factory()
            ->for($brand)
            ->create(['name' => 'Product 1']);
        $product1->categories()->attach($firstCategory);

        $product2 = Product::factory()
            ->for($brand)
            ->create(['name' => 'Product 2']);
        $product2->categories()->attach($secondCategory);

        livewire(Products::class)
            ->dispatch('filters::categories-updated', categories: [])
            ->assertSee($product1->name)
            ->assertSee($product2->name);
    });

    it('resets to first page when category filter is updated', function () {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $products = Product::factory(25)->for($brand)->create();
        foreach ($products as $product) {
            $product->categories()->attach($category);
        }

        $component = livewire(Products::class)
            ->call('gotoPage', 2)
            ->dispatch('filters::categories-updated', categories: [$category->id])
            ->assertSet('paginators.page', 1);
    });
});

describe('combined filters', function () {
    it('applies search and brand filters together', function () {
        [$appleBrand, $samsungBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        $macbook = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook Pro']);

        $iphone = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'iPhone 15']);

        $galaxy = Product::factory()
            ->for($samsungBrand)
            ->create(['name' => 'Galaxy S24']);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'Mac')
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->assertSee($macbook->name)
            ->assertDontSee($iphone->name)
            ->assertDontSee($galaxy->name);
    });

    it('applies search and category filters together', function () {
        [$electronicsCategory, $toysCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Toys']
            )
            ->create();

        $brand = Brand::factory()->create();

        $laptop = Product::factory()
            ->for($brand)
            ->create(['name' => 'Gaming Laptop']);
        $laptop->categories()->attach($electronicsCategory);

        $phone = Product::factory()
            ->for($brand)
            ->create(['name' => 'Smartphone']);
        $phone->categories()->attach($electronicsCategory);

        $gamingToy = Product::factory()
            ->for($brand)
            ->create(['name' => 'Gaming Console Toy']);
        $gamingToy->categories()->attach($toysCategory);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'Gaming')
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee($laptop->name)
            ->assertDontSee($phone->name)
            ->assertDontSee($gamingToy->name);
    });

    it('applies brand and category filters together', function () {
        [$appleBrand, $samsungBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        [$electronicsCategory, $accessoriesCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Accessories']
            )
            ->create();

        $macbook = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook']);
        $macbook->categories()->attach($electronicsCategory);

        $appleWatch = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'Apple Watch']);
        $appleWatch->categories()->attach($accessoriesCategory);

        $galaxyLaptop = Product::factory()
            ->for($samsungBrand)
            ->create(['name' => 'Galaxy Book']);
        $galaxyLaptop->categories()->attach($electronicsCategory);

        livewire(Products::class)
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee($macbook->name)
            ->assertDontSee($appleWatch->name)
            ->assertDontSee($galaxyLaptop->name);
    });

    it('applies all three filters together', function () {
        [$appleBrand, $samsungBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        [$electronicsCategory, $accessoriesCategory] = Category::factory(2)
            ->sequence(
                ['name' => 'Electronics'],
                ['name' => 'Accessories']
            )
            ->create();

        $macbookPro = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook Pro']);
        $macbookPro->categories()->attach($electronicsCategory);

        $macbookAir = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook Air']);
        $macbookAir->categories()->attach($electronicsCategory);

        $ipadPro = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'iPad Pro']);
        $ipadPro->categories()->attach($electronicsCategory);

        $appleWatch = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'Apple Watch']);
        $appleWatch->categories()->attach($accessoriesCategory);

        $galaxyBook = Product::factory()
            ->for($samsungBrand)
            ->create(['name' => 'Galaxy Book Pro']);
        $galaxyBook->categories()->attach($electronicsCategory);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'Pro')
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee($macbookPro->name)
            ->assertSee($ipadPro->name)
            ->assertDontSee($macbookAir->name)
            ->assertDontSee($appleWatch->name)
            ->assertDontSee($galaxyBook->name);
    });

    it('shows no products when combined filters match nothing', function () {
        $appleBrand = Brand::factory()->create(['name' => 'Apple']);
        $electronicsCategory = Category::factory()->create(['name' => 'Electronics']);

        $macbook = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook Pro']);
        $macbook->categories()->attach($electronicsCategory);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'Samsung')
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertDontSee($macbook->name);
    });
});

describe('pagination', function () {
    it('paginates products with 12 items per page', function () {
        $brand = Brand::factory()->create();

        Product::factory(25)->for($brand)->create();

        $component = livewire(Products::class);

        expect($component->get('products')->count())->toBe(12);
        expect($component->get('products')->total())->toBe(25);
    });

    it('maintains filters across pagination', function () {
        [$appleBrand, $samsungBrand] = Brand::factory(2)
            ->sequence(
                ['name' => 'Apple'],
                ['name' => 'Samsung']
            )
            ->create();

        Product::factory(20)->for($appleBrand)->create();
        Product::factory(5)->for($samsungBrand)->create();

        livewire(Products::class)
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->call('gotoPage', 2)
            ->assertSet('selectedBrands', [$appleBrand->id]);
    });
});

describe('url persistence', function () {
    it('maintains filters when set programmatically', function () {
        $brand = Brand::factory()->create();

        Product::factory()
            ->for($brand)
            ->create(['name' => 'MacBook Pro']);

        Product::factory()
            ->create(['name' => 'Dell XPS']);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'MacBook')
            ->dispatch('filters::brands-updated', brands: [$brand->id])
            ->assertSee('MacBook Pro')
            ->assertDontSee('Dell XPS');
    });

    it('can filter with multiple parameters simultaneously', function () {
        $electronicsCategory = Category::factory()->create(['name' => 'Electronics']);
        $appleBrand = Brand::factory()->create(['name' => 'Apple']);

        $macbook = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'MacBook Pro']);
        $macbook->categories()->attach($electronicsCategory);

        $iphone = Product::factory()
            ->for($appleBrand)
            ->create(['name' => 'iPhone']);
        $iphone->categories()->attach($electronicsCategory);

        livewire(Products::class)
            ->dispatch('filters::search-updated', search: 'MacBook')
            ->dispatch('filters::brands-updated', brands: [$appleBrand->id])
            ->dispatch('filters::categories-updated', categories: [$electronicsCategory->id])
            ->assertSee('MacBook Pro')
            ->assertDontSee('iPhone');
    });
});
