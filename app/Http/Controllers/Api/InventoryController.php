<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Get inventory report
     */
    public function report(Request $request): JsonResponse
    {
        $query = Product::query();

        // Filter by status
        if ($request->has('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query
            ->select('id', 'name', 'sku', 'stock', 'low_stock_threshold', 'stock_status', 'price')
            ->orderByRaw('CASE WHEN stock_status = "low_stock" THEN 1 WHEN stock_status = "out_of_stock" THEN 2 ELSE 3 END')
            ->paginate(20);

        $stats = [
            'total_products' => Product::count(),
            'in_stock' => Product::where('stock_status', 'in_stock')->count(),
            'low_stock' => Product::where('stock_status', 'low_stock')->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'total_inventory_value' => Product::selectRaw('SUM(stock * price) as total')->first()->total ?? 0,
        ];

        return response()->json([
            'stats' => $stats,
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Get low stock products
     */
    public function lowStock(Request $request): JsonResponse
    {
        $products = Product::where('stock_status', 'low_stock')
            ->orWhereRaw('stock <= low_stock_threshold')
            ->select('id', 'name', 'sku', 'stock', 'low_stock_threshold', 'price')
            ->orderBy('stock')
            ->paginate(15);

        return response()->json([
            'alert_count' => Product::where('stock_status', 'low_stock')->count(),
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Get out of stock products
     */
    public function outOfStock(Request $request): JsonResponse
    {
        $products = Product::where('stock_status', 'out_of_stock')
            ->select('id', 'name', 'sku', 'stock', 'price')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'out_of_stock_count' => Product::where('stock_status', 'out_of_stock')->count(),
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Get stock movement history for a product
     */
    public function movements(Product $product, Request $request): JsonResponse
    {
        $movements = $product->stockMovements()
            ->with('createdBy:id,name')
            ->paginate(20);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'current_stock' => $product->stock,
            ],
            'movements' => $movements->items(),
            'pagination' => [
                'total' => $movements->total(),
                'per_page' => $movements->perPage(),
                'current_page' => $movements->currentPage(),
                'last_page' => $movements->lastPage(),
            ]
        ]);
    }

    /**
     * Record stock movement
     */
    public function recordMovement(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:purchase,return,adjustment,damaged,lost',
            'quantity' => 'required|integer|not_in:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::find($request->product_id);

        // Record the movement
        $movement = StockMovement::record(
            $product,
            $request->movement_type,
            $request->quantity,
            $request->reference,
            $request->notes,
            auth('api')->id()
        );

        // Update product stock
        $newStock = max(0, $product->stock + $request->quantity);
        $product->update(['stock' => $newStock]);

        // Update stock status
        $stockStatus = match (true) {
            $newStock <= 0 => 'out_of_stock',
            $newStock <= $product->low_stock_threshold => 'low_stock',
            default => 'in_stock'
        };
        $product->update(['stock_status' => $stockStatus]);

        return response()->json([
            'message' => 'Stock movement recorded successfully',
            'movement' => $movement,
            'updated_product' => [
                'id' => $product->id,
                'stock' => $product->stock,
                'stock_status' => $product->stock_status,
            ]
        ], 201);
    }
}
