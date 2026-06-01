<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skincare',
                'slug' => 'skincare',
                'description' => 'Natural and herbal skincare products for all skin types. Includes face wash, masks, gels, and treatments.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Hair Care',
                'slug' => 'hair-care',
                'description' => 'Premium hair care products with natural herbs. Hair oil, shampoo, and specialized treatments.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Supplements',
                'slug' => 'supplements',
                'description' => 'Nutritional supplements and wellness products. Vitamins, minerals, and herbal formulations.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Ayurvedic',
                'slug' => 'ayurvedic',
                'description' => 'Traditional Ayurvedic medicines and formulations for holistic health.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}