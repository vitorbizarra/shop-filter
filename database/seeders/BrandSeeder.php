<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple'],
            ['name' => 'Samsung'],
            ['name' => 'Sony'],
            ['name' => 'LG'],
            ['name' => 'Microsoft'],
        ];

        Brand::factory(count($brands))
            ->sequence(...$brands)
            ->create();
    }
}
