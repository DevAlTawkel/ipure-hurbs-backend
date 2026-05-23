<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    /**
     * GET /api/brands
     *
     * All active brands ordered alphabetically.
     * Supports: ?letter=A   (filter by first letter, use '0-9' for numeric)
     */
    public function index(): AnonymousResourceCollection
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->withCount('products')
            ->when(request('letter'), fn ($q, $letter) => $q->byLetter($letter))
            ->orderBy('name')
            ->get();

        return BrandResource::collection($brands);
    }

    /**
     * GET /api/brands/index
     *
     * Returns the full A-Z grouped brand directory as used in the listing page.
     * Groups brands under their first letter key.
     * Numeric brands grouped under '0-9'.
     *
     * Response shape:
     * {
     *   "data": {
     *     "0-9": [ { id, name, slug, logo_url } ],
     *     "A":   [ ... ],
     *     ...
     *   }
     * }
     */
    public function directory(): JsonResponse
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'logo']);

        $grouped = $brands->groupBy(fn (Brand $b) => $b->letter_index);

        // Ensure numeric group key is '0-9', letters are sorted A-Z
        $letters = collect(['0-9'])
            ->merge(range('A', 'Z'))
            ->filter(fn ($letter) => $grouped->has($letter))
            ->values();

        $directory = $letters->mapWithKeys(fn ($letter) => [
            $letter => $grouped[$letter]->map(fn (Brand $b) => [
                'id'       => $b->id,
                'name'     => $b->name,
                'slug'     => $b->slug,
                'logo_url' => $b->logo
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($b->logo)
                    : null,
            ])->values(),
        ]);

        return response()->json([
            'data'    => $directory,
            'letters' => $letters->values(), // convenience: which letters have brands
        ]);
    }

    /**
     * GET /api/brands/{slug}
     *
     * Single brand detail.
     */
    public function show(Brand $brand): BrandResource
    {
        abort_unless($brand->is_active, 404);

        $brand->loadCount('products');

        return new BrandResource($brand);
    }

    /**
     * GET /api/brands/{slug}/products
     *
     * Paginated products for a specific brand (20/page).
     * Supports: ?sort=price_asc|price_desc|rating|newest  ?q=search
     */
    public function products(Brand $brand): AnonymousResourceCollection
    {
        abort_unless($brand->is_active, 404);

        $products = $brand->products()
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