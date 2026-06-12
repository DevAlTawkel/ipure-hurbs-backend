<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\CurrencyService;
use App\Services\GeoLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * GET /api/home
     *
     * Automatically detects currency from visitor IP.
     * Frontend can override with X-Currency header or ?currency= param.
     *
     * Returns: currency info + categories + featured + trending + new products
     * All prices are already converted to the detected currency.
     */
    public function __invoke(Request $request): JsonResponse
    {
        // ── Currency Detection ────────────────────────────────────────────
        $fx       = app(CurrencyService::class);
        $explicit = $request->header('X-Currency') ?? $request->query('currency');

        if ($explicit) {
            $currencyInfo = $fx->forCode(strtoupper($explicit));
        } else {
            $geo          = app(GeoLocationService::class)->detect($request->ip());
            $currencyInfo = $fx->forCountry($geo['country_code']);
        }

        // ── Products ──────────────────────────────────────────────────────
        $base = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug', 'images'])
            ->where('is_active', true);

        $featuredProducts = (clone $base)->where('is_featured', true)->latest()->limit(8)->get();
        $trendingProducts = (clone $base)->where('is_trending', true)->latest()->limit(8)->get();
        $newProducts      = (clone $base)->latest()->limit(4)->get();

        // ── Categories ────────────────────────────────────────────────────
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'currency' => [
                'code'   => $currencyInfo['code'],
                'symbol' => $currencyInfo['symbol'],
                'name'   => $currencyInfo['name'],
                'rate'   => $currencyInfo['rate'],
            ],
            'data' => [
                'categories'        => CategoryResource::collection($categories),
                'featured_products' => ProductResource::collection($featuredProducts),
                'trending_products' => ProductResource::collection($trendingProducts),
                'new_products'      => ProductResource::collection($newProducts),
            ],
        ]);
    }
}
