<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::create([
            'name' => 'BioQem Pharma',
            'slug' => 'bioqem-pharma',
            'description' => 'Natural Ayurvedic and herbal health products.',
            'is_active' => true,
        ]);
    }
}
