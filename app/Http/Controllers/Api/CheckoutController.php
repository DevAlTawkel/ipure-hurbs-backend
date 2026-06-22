<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    const FIRST_ORDER_DISCOUNT    = 0.20;   // 20%
    const FREE_SHIPPING_THRESHOLD = 100.00; // Free standard shipping on orders over $100
    const SHIPPING_CHARGE         = 30.00;  // Standard shipping under $100
    const EXPRESS_SHIPPING        = 40.00;  // Express delivery always $40

    /**
     * POST /api/checkout/initiate
     * Calculates totals and returns a Stripe Payment Intent.
     */
    public function initiate(Request $request): JsonResponse
    {
        $request->validate([
            'address'          => ['required', 'array'],
            'address.name'     => ['required', 'string'],
            'address.phone'    => ['required', 'string'],
            'address.line1'    => ['required', 'string'],
            'address.city'     => ['required', 'string'],
            'address.state'    => ['required', 'string'],
            'address.pincode'  => ['required', 'string'],
            'address.country'  => ['sometimes', 'string', 'size:2'],
            // Guest fields (only if not authenticated)
            'guest_email'      => ['required_without:_auth', 'nullable', 'email'],
            'guest_name'       => ['nullable', 'string'],
            'guest_phone'      => ['nullable', 'string'],
            'notes'            => ['nullable', 'string', 'max:500'],
            'shipping_type'    => ['nullable', 'in:standard,express'],
        ]);

        $cart = Cart::findOrCreateForRequest($request);
        $cart->load(['items.product']);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        // Validate stock availability
        foreach ($cart->items as $item) {
            if (! $item->product || ! $item->product->is_active) {
                return response()->json([
                    'message' => "Product '{$item->product?->name}' is no longer available.",
                ], 422);
            }
            if ($item->qty > $item->product->stock) {
                return response()->json([
                    'message' => "Only {$item->product->stock} unit(s) of '{$item->product->name}' available.",
                ], 422);
            }
        }

        $subtotal      = $cart->total();
        $customer      = $request->user('customer');
        $shippingType  = $request->input('shipping_type', 'standard');

        // 20% first-order discount
        $discountAmount = 0;
        $discountReason = null;

        if ($customer && $customer->isEligibleForFirstOrderDiscount()) {
            $discountAmount = round($subtotal * self::FIRST_ORDER_DISCOUNT, 2);
            $discountReason = '20% welcome discount on your first order';
        }

        $afterDiscount  = $subtotal - $discountAmount;
        $shippingCharge = $shippingType === 'express'
            ? self::EXPRESS_SHIPPING
            : ($afterDiscount >= self::FREE_SHIPPING_THRESHOLD ? 0.0 : self::SHIPPING_CHARGE);
        $total          = $afterDiscount + $shippingCharge;

        // Create Stripe Payment Intent
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => (int) round($total * 100), // cents
            'currency' => 'usd',
            'metadata' => [
                'cart_id'       => $cart->id,
                'customer_id'   => $customer?->id,
                'guest_email'   => $request->guest_email,
            ],
        ]);

        return response()->json([
            'client_secret'    => $intent->client_secret,
            'payment_intent_id' => $intent->id,
            'pricing'          => [
                'subtotal'              => $subtotal,
                'discount_amount'       => $discountAmount,
                'discount_reason'       => $discountReason,
                'shipping_type'         => $shippingType,
                'shipping_charge'       => $shippingCharge,
                'shipping_note'         => $shippingType === 'express'
                    ? 'Express delivery: $40'
                    : ($shippingCharge == 0 ? 'Free shipping on orders over $100' : 'Standard shipping: $30'),
                'total'                 => $total,
            ],
        ]);
    }

    /**
     * POST /api/checkout/confirm
     * Creates the order after Stripe payment succeeds.
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string'],
            'address'           => ['required', 'array'],
            'address.name'      => ['required', 'string'],
            'address.phone'     => ['required', 'string'],
            'address.line1'     => ['required', 'string'],
            'address.city'      => ['required', 'string'],
            'address.state'     => ['required', 'string'],
            'address.pincode'   => ['required', 'string'],
            'address.country'   => ['sometimes', 'string', 'size:2'],
            'guest_email'       => ['nullable', 'email'],
            'guest_name'        => ['nullable', 'string'],
            'guest_phone'       => ['nullable', 'string'],
            'notes'             => ['nullable', 'string'],
            'shipping_type'     => ['nullable', 'in:standard,express'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return response()->json(['message' => 'Payment has not been completed.'], 422);
        }

        // Prevent duplicate orders for the same payment intent
        if (Order::where('stripe_payment_intent_id', $intent->id)->exists()) {
            $order = Order::with('items')->where('stripe_payment_intent_id', $intent->id)->first();

            return response()->json(['data' => new OrderResource($order)]);
        }

        $cart = Cart::findOrCreateForRequest($request);
        $cart->load(['items.product']);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        $customer     = $request->user('customer');
        $addr         = $request->input('address');
        $subtotal     = $cart->total();
        $shippingType = $request->input('shipping_type', 'standard');

        $discountAmount = 0;
        $discountReason = null;

        if ($customer && $customer->isEligibleForFirstOrderDiscount()) {
            $discountAmount = round($subtotal * self::FIRST_ORDER_DISCOUNT, 2);
            $discountReason = '20% welcome discount on your first order';
        }

        $afterDiscount  = $subtotal - $discountAmount;
        $shippingCharge = $shippingType === 'express'
            ? self::EXPRESS_SHIPPING
            : ($afterDiscount >= self::FREE_SHIPPING_THRESHOLD ? 0.0 : self::SHIPPING_CHARGE);
        $total          = $afterDiscount + $shippingCharge;

        $order = DB::transaction(function () use (
            $cart, $customer, $intent, $addr,
            $subtotal, $discountAmount, $discountReason, $shippingCharge, $total, $request
        ) {
            $order = Order::create([
                'customer_id'              => $customer?->id,
                'guest_email'              => $customer ? null : $request->guest_email,
                'guest_name'               => $customer ? null : $request->guest_name,
                'guest_phone'              => $customer ? null : $request->guest_phone,
                'shipping_name'            => $addr['name'],
                'shipping_phone'           => $addr['phone'],
                'shipping_line1'           => $addr['line1'],
                'shipping_line2'           => $addr['line2'] ?? null,
                'shipping_city'            => $addr['city'],
                'shipping_state'           => $addr['state'],
                'shipping_country'         => $addr['country'] ?? 'IN',
                'shipping_pincode'         => $addr['pincode'],
                'subtotal'                 => $subtotal,
                'discount_amount'          => $discountAmount,
                'discount_reason'          => $discountReason,
                'shipping_charge'          => $shippingCharge,
                'total'                    => $total,
                'status'                   => Order::STATUS_CONFIRMED,
                'payment_method'           => 'stripe',
                'payment_status'           => Order::PAYMENT_PAID,
                'stripe_payment_intent_id' => $intent->id,
                'stripe_charge_id'         => $intent->latest_charge,
                'paid_at'                  => now(),
                'notes'                    => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'product_sku'   => $item->product->sku,
                    'product_image' => $item->product->image,
                    'qty'           => $item->qty,
                    'unit_price'    => $item->price_at_add,
                    'subtotal'      => $item->price_at_add * $item->qty,
                ]);

                // Decrement stock
                $item->product->decrement('stock', $item->qty);
            }

            $order->update(['inventory_decremented' => true]);

            // Mark first-order discount as used
            if ($customer && $discountAmount > 0) {
                $customer->update(['first_order_used' => true]);
            }

            // Clear cart
            $cart->items()->delete();

            return $order;
        });

        $order->load('items');

        $emailTo = $customer?->email ?? $request->guest_email;
        if ($emailTo) {
            Mail::to($emailTo)->queue(new OrderPlacedMail($order));
        }

        return response()->json(['data' => new OrderResource($order)], 201);
    }
}
