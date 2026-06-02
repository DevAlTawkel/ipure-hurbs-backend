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

        $mensHealth  = Category::where('slug', 'mens-health-enhancer')->value('id');
        $diabetic    = Category::where('slug', 'diabetic-care')->value('id');
        $liverDetox  = Category::where('slug', 'liver-care-detoxification')->value('id');

        $products = [
            [
                'category_id'         => $mensHealth,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-MHE-001',
                'barcode'             => '0850012340001',
                'name'                => 'Vitalize Men\'s Capsules',
                'slug'                => 'vitalize-mens-capsules',
                'short_description'   => 'Advanced Ayurvedic formula for male vitality & strength',
                'description'         => 'A powerful blend of Ashwagandha KSM-66, Shilajit, and Safed Musli designed to enhance male vitality, stamina, and reproductive health. Supports healthy testosterone levels, reduces fatigue, and improves physical performance. 500 mg per capsule, 60 capsules per bottle.',
                'price'               => 24.99,
                'compare_price'       => 34.99,
                'sale_price'          => 19.99,
                'stock'               => 150,
                'low_stock_threshold' => 20,
                'stock_status'        => 'in_stock',
                'rating'              => 4.6,
                'review_count'        => 54,
                'sales_count'         => 310,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => true,
                'seo_title'           => 'Vitalize Men\'s Capsules – Ayurvedic Male Vitality – IPure Herbs',
                'seo_description'     => 'Buy Vitalize Men\'s Capsules with Ashwagandha & Shilajit for strength, stamina, and vitality. 100% natural. Free shipping on orders over $50.',
            ],
            [
                'category_id'         => $diabetic,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-DBC-002',
                'barcode'             => '0850012340002',
                'name'                => 'GlucoBalance Pro Tablets',
                'slug'                => 'glucobalance-pro-tablets',
                'short_description'   => 'Natural blood sugar management with Karela & Jamun',
                'description'         => 'A clinically inspired herbal formulation with Karela (Bitter Melon), Jamun Seed, Gurmar, and Methi to support healthy blood glucose levels. Helps improve insulin sensitivity, reduce sugar cravings, and promote pancreatic health. 60 tablets per pack.',
                'price'               => 27.99,
                'compare_price'       => 39.99,
                'sale_price'          => 22.99,
                'stock'               => 100,
                'low_stock_threshold' => 15,
                'stock_status'        => 'in_stock',
                'rating'              => 4.4,
                'review_count'        => 41,
                'sales_count'         => 198,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => false,
                'seo_title'           => 'GlucoBalance Pro Tablets – Natural Diabetic Care – IPure Herbs',
                'seo_description'     => 'Herbal blood sugar management tablets with Karela, Jamun, and Gurmar. Safe, natural, and effective diabetic care supplement.',
            ],
            [
                'category_id'         => $liverDetox,
                'brand_id'            => $brand,
                'sku'                 => 'IPH-LCD-003',
                'barcode'             => '0850012340003',
                'name'                => 'LiverGuard Detox Syrup',
                'slug'                => 'liverguard-detox-syrup',
                'short_description'   => 'Complete liver support & detoxification formula',
                'description'         => 'A comprehensive Ayurvedic liver tonic with Bhumi Amla, Kutki, Punarnava, and Giloy. Supports liver detoxification, stimulates healthy bile production, and protects hepatic cells from oxidative stress. Ideal for fatty liver, jaundice recovery, and daily liver maintenance. 200 ml per bottle.',
                'price'               => 19.99,
                'compare_price'       => 29.99,
                'sale_price'          => 16.99,
                'stock'               => 120,
                'low_stock_threshold' => 20,
                'stock_status'        => 'in_stock',
                'rating'              => 4.3,
                'review_count'        => 37,
                'sales_count'         => 245,
                'is_active'           => true,
                'is_featured'         => true,
                'is_trending'         => true,
                'seo_title'           => 'LiverGuard Detox Syrup – Ayurvedic Liver Care – IPure Herbs',
                'seo_description'     => 'Natural liver detox syrup with Bhumi Amla & Kutki. Supports liver function, detoxification, and hepatic health.',
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
