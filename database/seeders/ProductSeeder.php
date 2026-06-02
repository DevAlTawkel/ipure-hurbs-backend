<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::where('slug', 'ipure-herbs')->value('id');

        $menWellness    = Category::where('slug', 'men-wellness')->value('id');
        $womenWellness  = Category::where('slug', 'women-wellness')->value('id');
        $liverCare      = Category::where('slug', 'liver-care')->value('id');

        $products = [
            [
                'category_id'         => $menWellness,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-MW-001',
                'barcode'             => '0850012340001',
                'name'                => 'Ashwagandha Vitality Capsules',
                'slug'                => 'ashwagandha-vitality-capsules',
                'short_description'   => 'Ayurvedic strength & stamina formula for men',
                'description'         => 'Premium Ashwagandha (KSM-66) extract capsules for men\'s strength, stamina, and daily vitality. Helps reduce stress, supports healthy testosterone levels, and improves energy. 500 mg per capsule, 60 capsules per bottle.',
                'price'               => 24.99,
                'compare_price'       => 34.99,
                'sale_price'          => 19.99,
                'stock'               => 150,
                'low_stock_threshold' => 20,
                'stock_status'        => 'in_stock',
                'rating'              => 4.5,
                'review_count'        => 48,
                'sales_count'         => 320,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => true,
                'seo_title'           => 'Ashwagandha Vitality Capsules – IPure Herbs',
                'seo_description'     => 'Buy KSM-66 Ashwagandha capsules for men\'s strength, stamina and stress relief. 100% natural. Free shipping on orders over $50.',
            ],
            [
                'category_id'         => $womenWellness,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-WW-002',
                'barcode'             => '0850012340002',
                'name'                => 'Herbal Balance Tablets',
                'slug'                => 'herbal-balance-tablets',
                'short_description'   => 'Supports hormonal balance and women\'s overall health',
                'description'         => 'A carefully crafted blend of Shatavari, Lodhra, and Ashoka for women\'s hormonal harmony. Helps regulate cycles, supports reproductive health, and boosts energy levels naturally. Suitable for all adult women.',
                'price'               => 29.99,
                'compare_price'       => 44.99,
                'sale_price'          => 24.99,
                'stock'               => 120,
                'low_stock_threshold' => 15,
                'stock_status'        => 'in_stock',
                'rating'              => 4.8,
                'review_count'        => 62,
                'sales_count'         => 215,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => false,
                'seo_title'           => 'Herbal Balance Tablets for Women – IPure Herbs',
                'seo_description'     => 'Natural hormonal balance tablets with Shatavari and Ashoka. Supports women\'s wellness and reproductive health.',
            ],
            [
                'category_id'         => $liverCare,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-LC-003',
                'barcode'             => '0850012340003',
                'name'                => 'Liver Detox DS',
                'slug'                => 'liver-detox-ds',
                'short_description'   => 'Natural liver support and detoxification',
                'description'         => 'A powerful herbal formula with Kutki, Bhumi Amla, and Punarnava for comprehensive liver care. Supports liver detoxification, promotes healthy bile production, and protects liver cells from damage. Ideal for those with high-fat diets or alcohol exposure.',
                'price'               => 19.99,
                'compare_price'       => 29.99,
                'sale_price'          => 16.99,
                'stock'               => 200,
                'low_stock_threshold' => 25,
                'stock_status'        => 'in_stock',
                'rating'              => 4.2,
                'review_count'        => 34,
                'sales_count'         => 178,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => true,
                'seo_title'           => 'Liver Detox DS – Natural Liver Care – IPure Herbs',
                'seo_description'     => 'Herbal liver detox supplement with Kutki and Bhumi Amla. Supports liver health and detoxification naturally.',
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
