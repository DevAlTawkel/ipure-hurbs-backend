<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'IPure Herbs',
                'slug' => 'ipure-herbs',
                'description' => 'Premium natural and herbal products for health and wellness. Committed to quality and purity.',
                'logo' => null,
                'website' => 'https://ipurehers.com',
                'is_active' => true,
            ],
            [
                'name' => 'Himalaya',
                'slug' => 'himalaya',
                'description' => 'World-renowned Ayurvedic brand with 90+ years of heritage.',
                'logo' => null,
                'website' => 'https://himalayawellness.in',
                'is_active' => true,
            ],
            [
                'name' => 'Dabur',
                'slug' => 'dabur',
                'description' => 'Leading Ayurvedic pharmaceutical company in India.',
                'logo' => null,
                'website' => 'https://www.dabur.com',
                'is_active' => true,
            ],
            [
                'name' => 'Patanjali',
                'slug' => 'patanjali',
                'description' => 'Ayurveda-based FMCG company with organic products.',
                'logo' => null,
                'website' => 'https://www.patanjaliayurved.net',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(['slug' => $brand['slug']], $brand);
        }
    }
}