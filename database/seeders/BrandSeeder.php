<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::create([
            'name' => 'Himalaya',
            'slug' => 'himalaya',
        ]);

        Brand::create([
            'name' => 'Dabur',
            'slug' => 'dabur',
        ]);
    }
}