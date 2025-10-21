<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Smartphones'],
            ['name' => 'Tablets'],
            ['name' => 'Laptops'],
            ['name' => 'Desktops'],
            ['name' => 'Monitors'],
            ['name' => 'Headphones'],
            ['name' => 'Speakers'],
            ['name' => 'Smartwatches'],
            ['name' => 'E-readers'],
            ['name' => 'Keyboards'],
            ['name' => 'Mice'],
            ['name' => 'Webcams'],
            ['name' => 'Gaming Consoles'],
            ['name' => 'Smart Home'],
            ['name' => 'Chargers & Cables'],
        ];

        Category::factory(count($categories))
            ->sequence(...$categories)
            ->create();
    }
}
