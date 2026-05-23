<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user('customer')
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json(OrderResource::collection($orders)->response()->getData(true));
    }

    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = $request->user('customer')
            ->orders()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json(['data' => new OrderResource($order)]);
    }

    public function cancel(Request $request, string $orderNumber): JsonResponse
    {
        $order = $request->user('customer')
            ->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (! $order->canBeCancelled()) {
            return response()->json([
                'message' => 'This order cannot be cancelled at this stage.',
            ], 422);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->qty);
            }
        }

        return response()->json(['message' => 'Order cancelled.', 'data' => new OrderResource($order)]);
    }
}
