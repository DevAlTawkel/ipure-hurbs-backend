<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->load(['items.product']);

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty'        => ['integer', 'min:1', 'max:100'],
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        if ($product->stock < 1) {
            return response()->json(['message' => 'Product is out of stock.'], 422);
        }

        $qty = $data['qty'] ?? 1;

        if ($qty > $product->stock) {
            return response()->json([
                'message' => "Only {$product->stock} unit(s) available.",
            ], 422);
        }

        $cart = Cart::findOrCreateForRequest($request);

        $existing = $cart->items()->where('product_id', $product->id)->first();

        if ($existing) {
            $newQty = $existing->qty + $qty;
            if ($newQty > $product->stock) {
                return response()->json([
                    'message' => "Cannot add more. Only {$product->stock} unit(s) available.",
                ], 422);
            }
            $existing->update(['qty' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id'   => $product->id,
                'qty'          => $qty,
                'price_at_add' => $product->price,
            ]);
        }

        $cart->load(['items.product']);

        return response()->json([
            'message'    => 'Item added to cart.',
            'data'       => new CartResource($cart),
            'cart_token' => $cart->session_token,
        ]);
    }

    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $cart = Cart::findOrCreateForRequest($request);
        $item = $cart->items()->with('product')->findOrFail($itemId);

        if ($data['qty'] > $item->product->stock) {
            return response()->json([
                'message' => "Only {$item->product->stock} unit(s) available.",
            ], 422);
        }

        $item->update(['qty' => $data['qty']]);
        $cart->load(['items.product']);

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function removeItem(Request $request, int $itemId): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->items()->findOrFail($itemId)->delete();
        $cart->load(['items.product']);

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared.']);
    }
}
