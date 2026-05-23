<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * GET /api/home
     *
     * Single endpoint that returns everything the home page needs:
     *   - categories (all active, ordered)
     *   - featured_products (up to 8)
     *   - trending_products (up to 8)
     *   - new_products (latest 4)
     */
    public function __invoke(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $base = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true);

        $featuredProducts  = (clone $base)->where('is_featured', true)->latest()->limit(8)->get();
        $trendingProducts  = (clone $base)->where('is_trending', true)->latest()->limit(8)->get();
        $newProducts       = (clone $base)->latest()->limit(4)->get();

        return response()->json([
            'data' => [
                'categories'        => CategoryResource::collection($categories),
                'featured_products' => ProductResource::collection($featuredProducts),
                'trending_products' => ProductResource::collection($trendingProducts),
                'new_products'      => ProductResource::collection($newProducts),
            ],
        ]);
    }
}