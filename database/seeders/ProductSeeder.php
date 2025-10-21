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

        Product::factory(50)
            ->recycle($brands)
            ->create()
            ->each(fn (Product $product) => $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')
            ));
    }
}
