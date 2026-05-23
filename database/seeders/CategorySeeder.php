<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Herbal Oils',
            'slug' => 'herbal-oils',
        ]);

        Category::create([
            'name' => 'Ayurvedic Supplements',
            'slug' => 'ayurvedic-supplements',
        ]);
    }
}