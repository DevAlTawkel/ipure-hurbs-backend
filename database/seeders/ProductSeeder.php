<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'brand_id' => 1,
            'name' => 'Herbal Hair Oil',
            'slug' => 'herbal-hair-oil',
            'description' => 'Natural herbal oil for healthy hair.',
            'price' => 299,
            'rating' => 4.5,
            'stock' => 50,
            'is_active' => true,
            'is_featured' => true,
            'is_trending' => true,
        ]);

        Product::create([
            'category_id' => 2,
            'brand_id' => 2,
            'name' => 'Ashwagandha Capsules',
            'slug' => 'ashwagandha-capsules',
            'description' => 'Ayurvedic stress relief supplement.',
            'price' => 499,
            'rating' => 4.8,
            'stock' => 100,
            'is_active' => true,
            'is_featured' => true,
            'is_trending' => false,
        ]);
    }
}