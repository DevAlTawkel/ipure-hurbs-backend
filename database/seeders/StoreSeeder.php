<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => "Men's Wellness", 'slug' => 'men-wellness', 'sort_order' => 1],
            ['name' => "Women's Wellness", 'slug' => 'women-wellness', 'sort_order' => 2],
            ['name' => 'Digestive Health', 'slug' => 'digestive-health', 'sort_order' => 3],
            ['name' => 'Weight Management', 'slug' => 'weight-management', 'sort_order' => 4],
            ['name' => 'Liver Care', 'slug' => 'liver-care', 'sort_order' => 5],
        ];

        foreach ($categories as $data) {
            Category::query()->firstOrCreate(['slug' => $data['slug']], $data);
        }

        $men = Category::query()->where('slug', 'men-wellness')->first();
        $women = Category::query()->where('slug', 'women-wellness')->first();

        $products = [
            [
                'category_id' => $men?->id,
                'name' => 'Ashwagandha Vitality Capsules',
                'slug' => 'ashwagandha-vitality-capsules',
                'description' => 'Ayurvedic formula for strength, stamina and daily wellness.',
                'price' => 499.00,
                'rating' => 4.5,
                'stock' => 50,
                'is_featured' => true,
                'is_trending' => true,
            ],
            [
                'category_id' => $women?->id,
                'name' => 'Herbal Balance Tablets',
                'slug' => 'herbal-balance-tablets',
                'description' => 'Supports hormonal balance and overall women\'s health.',
                'price' => 649.00,
                'rating' => 4.8,
                'stock' => 35,
                'is_featured' => true,
                'is_trending' => false,
            ],
            [
                'category_id' => $men?->id,
                'name' => 'Shilajit Gold Resin',
                'slug' => 'shilajit-gold-resin',
                'description' => 'Premium shilajit for energy and vitality.',
                'price' => 899.00,
                'rating' => 5.0,
                'stock' => 20,
                'is_featured' => false,
                'is_trending' => true,
            ],
            [
                'category_id' => Category::query()->where('slug', 'liver-care')->value('id'),
                'name' => 'Liver Detox DS',
                'slug' => 'liver-detox-ds',
                'description' => 'Natural liver care and detoxification support.',
                'price' => 350.00,
                'rating' => 4.2,
                'stock' => 60,
                'is_featured' => true,
                'is_trending' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::query()->firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
