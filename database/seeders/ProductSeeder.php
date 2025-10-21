<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();
        $categories = Category::all();

        $brands->each(function (Brand $brand) use ($categories) {
            $names = collect([
                'Notebook',
                'Smartphone',
                'Tablet',
                'Monitor',
                'Headphones',
                'Smartwatch',
                'Speaker',
                'Desktop PC',
                'Gaming Console',
                'E-reader',
            ])->take(rand(3, 10));

            Product::factory($names->count())
                ->for($brand)
                ->sequence(...$names->map(fn (string $name) => ['name' => "$name"]))
                ->create()
                ->each(fn (Product $product) => $product->categories()->attach(
                    $categories->random(rand(1, 2))->pluck('id')
                ));
        });
    }
}
