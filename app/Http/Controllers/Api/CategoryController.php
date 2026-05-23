<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     *
     * All active categories ordered by sort_order.
     * Includes a product_count for each.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return CategoryResource::collection($categories);
    }

    /**
     * GET /api/categories/{slug}
     *
     * Single category detail.
     */
    public function show(Category $category): CategoryResource
    {
        abort_unless($category->is_active, 404);

        $category->loadCount('products');

        return new CategoryResource($category);
    }

    /**
     * GET /api/categories/{slug}/products
     *
     * All active products in a specific category (paginated, 20/page).
     * Supports: ?sort=price_asc|price_desc|rating|newest  ?q=search
     */
    public function products(Category $category): AnonymousResourceCollection
    {
        abort_unless($category->is_active, 404);

        $products = $category->products()
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->where('is_active', true)
            ->when(request('q'), fn ($q, $search) => $q->where(
                fn ($inner) => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            ))
            ->when(request('sort'), function ($q, $sort) {
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
}