<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::updateOrCreate(['slug' => 'bioqem-pharma'], [
            'name'        => 'BioQem Pharma',
            'slug'        => 'bioqem-pharma',
            'description' => 'Natural Ayurvedic and herbal health products for holistic wellness.',
            'logo'        => null,
            'website'     => 'https://ipureherbs.com',
            'is_active'   => true,
        ]);
    }
}
