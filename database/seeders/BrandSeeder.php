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
            ['name' => 'Dell'],
            ['name' => 'HP'],
            ['name' => 'Lenovo'],
            ['name' => 'Asus'],
            ['name' => 'Acer'],
            ['name' => 'Google'],
            ['name' => 'Amazon'],
            ['name' => 'Xiaomi'],
            ['name' => 'Huawei'],
            ['name' => 'OnePlus'],
            ['name' => 'Motorola'],
            ['name' => 'Nokia'],
            ['name' => 'Bose'],
            ['name' => 'JBL'],
            ['name' => 'Philips'],
        ];

        Brand::factory(count($brands))
            ->sequence(...$brands)
            ->create();
    }
}
