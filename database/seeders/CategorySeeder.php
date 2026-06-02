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
                'name'        => "Men's Health Enhancer",
                'slug'        => 'mens-health-enhancer',
                'description' => 'Ayurvedic and herbal formulations to enhance male vitality, stamina, strength, and reproductive health.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => "Women's Health Enhancer",
                'slug'        => 'womens-health-enhancer',
                'description' => 'Natural herbal products for women\'s hormonal balance, reproductive health, and overall wellbeing.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'Digestive Health Enhancer',
                'slug'        => 'digestive-health-enhancer',
                'description' => 'Herbal supplements to support healthy digestion, gut flora, acidity relief, and bowel wellness.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => 'Diabetic Care',
                'slug'        => 'diabetic-care',
                'description' => 'Natural formulations with Karela, Jamun, and Gurmar to support healthy blood sugar levels.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'name'        => 'Heart Care',
                'slug'        => 'heart-care',
                'description' => 'Cardio-protective herbal supplements to support healthy cholesterol, blood pressure, and heart function.',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'name'        => 'Liver Care & Detoxification',
                'slug'        => 'liver-care-detoxification',
                'description' => 'Ayurvedic liver support and detox products with Bhumi Amla, Kutki, and Punarnava for optimal liver function.',
                'sort_order'  => 6,
                'is_active'   => true,
            ],
            [
                'name'        => 'Kidney Care',
                'slug'        => 'kidney-care',
                'description' => 'Natural herbal products to support kidney function, urinary health, and stone prevention.',
                'sort_order'  => 7,
                'is_active'   => true,
            ],
            [
                'name'        => 'Respiratory Care & Immunity Booster',
                'slug'        => 'respiratory-care-immunity-booster',
                'description' => 'Herbal formulations to strengthen immunity, support respiratory health, and provide relief from cough and congestion.',
                'sort_order'  => 8,
                'is_active'   => true,
            ],
            [
                'name'        => 'Bone & Joint Wellness',
                'slug'        => 'bone-joint-wellness',
                'description' => 'Natural supplements with Shallaki, Guggul, and Turmeric to support joint mobility, bone strength, and arthritis relief.',
                'sort_order'  => 9,
                'is_active'   => true,
            ],
            [
                'name'        => 'Weight Management',
                'slug'        => 'weight-management',
                'description' => 'Herbal weight management solutions to support healthy metabolism, fat burning, and appetite control.',
                'sort_order'  => 10,
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
