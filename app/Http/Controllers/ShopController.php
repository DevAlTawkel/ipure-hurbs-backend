<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function home(): View
    {
        return view('shop.home', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'featuredProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->limit(8)
                ->get(),
            'trendingProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->where('is_trending', true)
                ->latest()
                ->limit(8)
                ->get(),
            'newProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->latest()
                ->limit(4)
                ->get(),
        ]);
    }

    public function products(): View
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when(request('category'), fn ($q, $slug) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $slug)
            ))
            ->when(request('q'), fn ($q, $search) => $q->where(
                fn ($inner) => $inner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
            ))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('shop.products', [
            'products' => $products,
            'categories' => Category::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'activeCategory' => request('category'),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('category');

        return view('shop.product', [
            'product' => $product,
            'relatedProducts' => Product::query()
                ->where('is_active', true)
                ->where('id', '!=', $product->id)
                ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
                ->limit(4)
                ->get(),
        ]);
    }
}
