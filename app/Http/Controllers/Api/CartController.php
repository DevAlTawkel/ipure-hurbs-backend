<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->load(['items.product', 'items.variant']);

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'qty'        => ['integer', 'min:1', 'max:100'],
        ]);

        $product = Product::where('id', $data['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $variant   = null;
        $variantId = $data['variant_id'] ?? null;
        $price     = $product->price;
        $stock     = $product->stock;

        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->firstOrFail();
            $price = $variant->sale_price ?? $variant->price;
            $stock = $variant->stock;
        }

        if ($stock < 1) {
            return response()->json(['message' => 'Product is out of stock.'], 422);
        }

        $qty = $data['qty'] ?? 1;

        if ($qty > $stock) {
            return response()->json([
                'message' => "Only {$stock} unit(s) available.",
            ], 422);
        }

        $cart = Cart::findOrCreateForRequest($request);

        $existing = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->first();

        if ($existing) {
            $newQty = $existing->qty + $qty;
            if ($newQty > $stock) {
                return response()->json([
                    'message' => "Cannot add more. Only {$stock} unit(s) available.",
                ], 422);
            }
            $existing->update(['qty' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id'   => $product->id,
                'variant_id'   => $variantId,
                'qty'          => $qty,
                'price_at_add' => $price,
            ]);
        }

        $cart->load(['items.product', 'items.variant']);

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
        $cart->load(['items.product', 'items.variant']);

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function removeItem(Request $request, int $itemId): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->items()->findOrFail($itemId)->delete();
        $cart->load(['items.product', 'items.variant']);

        return response()->json(['data' => new CartResource($cart)]);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = Cart::findOrCreateForRequest($request);
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared.']);
    }
}
