<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Weight Management',                    'slug' => 'weight-management'],
            ['name' => 'Bone & Joints Wellness',               'slug' => 'bone-joints-wellness'],
            ['name' => 'Respiratory Care & Immunity Booster',  'slug' => 'respiratory-care-immunity-booster'],
            ['name' => 'Kidney Care',                          'slug' => 'kidney-care'],
            ['name' => 'Liver Care & Detoxification',          'slug' => 'liver-care-detoxification'],
            ['name' => 'Heart Care',                           'slug' => 'heart-care'],
            ['name' => 'Diabetic Care',                        'slug' => 'diabetic-care'],
            ['name' => 'Digestive Health Enhancer',            'slug' => 'digestive-health-enhancer'],
            ['name' => "Women's Health Enhancer",              'slug' => 'womens-health-enhancer'],
            ['name' => "Men's Health Enhancer",                'slug' => 'mens-health-enhancer'],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, ['is_active' => true]));
        }
    }
}
