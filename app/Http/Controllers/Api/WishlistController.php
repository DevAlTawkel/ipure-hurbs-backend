<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get customer's wishlist
     */
    public function index(Request $request): JsonResponse
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $wishlists = $customer->wishlists()
            ->with(['product.category:id,name,slug', 'product.brand:id,name,slug'])
            ->paginate(15);

        $products = $wishlists->getCollection()->map(fn ($w) => $w->product)->filter();

        return response()->json([
            'data' => ProductResource::collection($products)->resolve(),
            'pagination' => [
                'total' => $wishlists->total(),
                'per_page' => $wishlists->perPage(),
                'current_page' => $wishlists->currentPage(),
                'last_page' => $wishlists->lastPage(),
            ]
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function store(Request $request): JsonResponse
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::updateOrCreate([
            'customer_id' => $customer->id,
            'product_id' => $request->product_id,
        ]);

        $product = Product::with(['category:id,name,slug', 'brand:id,name,slug'])
            ->find($request->product_id);

        return response()->json([
            'message' => 'Product added to wishlist',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(Request $request): JsonResponse
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['message' => 'Product removed from wishlist']);
    }

    /**
     * Get wishlist count
     */
    public function count(): JsonResponse
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return response()->json(['count' => 0]);
        }

        return response()->json([
            'count' => $customer->wishlists()->count(),
        ]);
    }
}
