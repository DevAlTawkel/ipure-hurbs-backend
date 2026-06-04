<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * GET /api/products
     *
     * Query params:
     *   ?category=slug        filter by category slug
     *   ?brand=slug           filter by brand slug
     *   ?q=search             search name & description
     *   ?featured=1           only featured products
     *   ?trending=1           only trending products
     *   ?sort=price_asc|price_desc|rating|newest   (default: newest)
     *   ?page=1               20 per page
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)

            ->when($request->category, fn ($q, $slug) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $slug)
            ))

            ->when($request->brand, fn ($q, $slug) => $q->whereHas(
                'brand',
                fn ($b) => $b->where('slug', $slug)
            ))

            ->when($request->q, fn ($q, $search) => $q->where(
                fn ($inner) => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            ))

            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
            ->when($request->boolean('trending'), fn ($q) => $q->where('is_trending', true))

            ->when($request->sort, function ($q, $sort) {
                return match ($sort) {
                    'price_asc'  => $q->orderBy('price', 'asc'),
                    'price_desc' => $q->orderBy('price', 'desc'),
                    'rating'     => $q->orderBy('rating', 'desc'),
                    default      => $q->latest(),
                };
            }, fn ($q) => $q->latest())

            ->paginate(20)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * GET /api/products/featured
     * Returns up to 8 featured products for the home page.
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(8)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * GET /api/products/trending
     * Returns up to 8 trending products for the home page.
     */
    public function trending(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)
            ->where('is_trending', true)
            ->latest()
            ->limit(8)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * GET /api/products/{slug}
     * Full product detail — includes variants, sections, images, reviews, related products.
     */
    public function show(Product $product): ProductResource
    {
        abort_unless($product->is_active, 404);

        $product->load([
            'category',
            'brand',
            'images',
            'variants'  => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'sections'  => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'reviews'   => fn ($q) => $q->with('customer:id,name')->latest()->limit(20),
        ]);

        // Attach related products as extra data
        $product->setRelation('related', Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->orderByDesc('sales_count')
            ->limit(8)
            ->get()
        );

        return new ProductResource($product);
    }

    /**
     * GET /api/products/{slug}/related
     * Up to 8 active products from the same category, excluding this one.
     */
    public function related(Product $product): AnonymousResourceCollection
    {
        abort_unless($product->is_active, 404);

        $related = Product::query()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->limit(8)
            ->get();

        return ProductResource::collection($related);
    }
}