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
                'name' => "Men's Wellness",
                'slug' => 'men-wellness',
                'description' => 'Ayurvedic and herbal products designed to support men\'s health, strength, stamina, and vitality.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => "Women's Wellness",
                'slug' => 'women-wellness',
                'description' => 'Natural herbal formulations for women\'s hormonal balance, energy, and overall wellbeing.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Digestive Health',
                'slug' => 'digestive-health',
                'description' => 'Herbal remedies and supplements to support healthy digestion, gut health, and detoxification.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Weight Management',
                'slug' => 'weight-management',
                'description' => 'Natural herbs and formulations to support healthy weight, metabolism, and fat management.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Liver Care',
                'slug' => 'liver-care',
                'description' => 'Ayurvedic liver support and detox products for optimal liver function and cleansing.',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}