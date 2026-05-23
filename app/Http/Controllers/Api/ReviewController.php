<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /api/products/{product}/reviews
     */
    public function index(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $reviews = $product->reviews()
            ->with('customer:id,name')
            ->latest()
            ->paginate(10);

        return response()->json(ReviewResource::collection($reviews)->response()->getData(true));
    }

    /**
     * POST /api/products/{product}/reviews
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $customer = $request->user('customer');

        $data = $request->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'title'    => ['nullable', 'string', 'max:150'],
            'body'     => ['nullable', 'string', 'max:2000'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        if ($customer && $data['order_id'] ?? false) {
            $order = $customer->orders()
                ->where('id', $data['order_id'])
                ->where('payment_status', 'paid')
                ->first();

            $data['is_verified_purchase'] = $order && $order->items()->where('product_id', $product->id)->exists();
        }

        // One review per customer per product
        if ($customer) {
            $already = Review::where('product_id', $product->id)
                ->where('customer_id', $customer->id)
                ->exists();

            if ($already) {
                return response()->json(['message' => 'You have already reviewed this product.'], 422);
            }
        }

        $review = Review::create([
            'product_id'           => $product->id,
            'customer_id'          => $customer?->id,
            'order_id'             => $data['order_id'] ?? null,
            'rating'               => $data['rating'],
            'title'                => $data['title'] ?? null,
            'body'                 => $data['body'] ?? null,
            'is_verified_purchase' => $data['is_verified_purchase'] ?? false,
            'is_approved'          => false,
        ]);

        return response()->json(['data' => new ReviewResource($review)], 201);
    }
}
