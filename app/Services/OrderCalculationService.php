<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class OrderCalculationService
{
    public const FREE_SHIPPING_THRESHOLD = 100.00;

    public const SHIPPING_STANDARD = 30.00;

    public const SHIPPING_EXPRESS = 40.00;

    /**
     * @param  array<int, array{product_id: int, variant_id?: int|null, quantity: int}>  $items
     * @return array{
     *     items: Collection<int, array<string, mixed>>,
     *     subtotal: float,
     *     shipping_method: string,
     *     shipping_charge: float,
     *     discount: float,
     *     discount_reason: string|null,
     *     grand_total: float,
     *     is_free_shipping: bool
     * }
     */
    public function calculate(array $items, string $shippingMethod, ?string $promoCode = null): array
    {
        $resolvedItems = $this->resolveItems($items);
        $subtotal      = round($resolvedItems->sum('line_subtotal'), 2);

        $discount       = 0.0;
        $discountReason = null;

        if ($promoCode) {
            $coupon = Coupon::where('code', strtoupper($promoCode))->first();

            if (! $coupon || ! $coupon->isValid()) {
                throw ValidationException::withMessages([
                    'promo_code' => ['This promo code is invalid or expired.'],
                ]);
            }

            if ($coupon->minimum_spend && $subtotal < $coupon->minimum_spend) {
                throw ValidationException::withMessages([
                    'promo_code' => ["Minimum order amount of \${$coupon->minimum_spend} required."],
                ]);
            }

            $discount       = round($coupon->calculateDiscount($subtotal), 2);
            $discountReason = $coupon->code;
        }

        $shippingCharge  = $this->calculateShippingCharge($subtotal, $shippingMethod);
        $isFreeShipping  = $shippingCharge === 0.0;
        $grandTotal      = round(max($subtotal - $discount, 0) + $shippingCharge, 2);

        return [
            'items'             => $resolvedItems,
            'subtotal'          => $subtotal,
            'shipping_method'   => $shippingMethod,
            'shipping_charge'   => $shippingCharge,
            'discount'          => $discount,
            'discount_reason'   => $discountReason,
            'grand_total'       => $grandTotal,
            'is_free_shipping'  => $isFreeShipping,
        ];
    }

    /**
     * @param  array<int, array{product_id: int, variant_id?: int|null, quantity: int}>  $items
     */
    public function resolveItems(array $items): Collection
    {
        if ($items === []) {
            throw ValidationException::withMessages([
                'items' => ['At least one item is required.'],
            ]);
        }

        return collect($items)->map(function (array $item) {
            $quantity = (int) $item['quantity'];

            $product = Product::query()
                ->where('id', $item['product_id'])
                ->where('is_active', true)
                ->first();

            if (! $product) {
                throw ValidationException::withMessages([
                    'items' => ["Product #{$item['product_id']} is not available."],
                ]);
            }

            $variantId = $item['variant_id'] ?? null;
            $price     = (float) ($product->sale_price ?? $product->price);
            $stock     = $product->stock;
            $sku       = $product->sku;
            $name      = $product->name;
            $image     = $product->image;

            if ($variantId) {
                $variant = ProductVariant::query()
                    ->where('id', $variantId)
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->first();

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'items' => ["Variant #{$variantId} is not available for this product."],
                    ]);
                }

                $price = (float) ($variant->sale_price ?? $variant->price);
                $stock = $variant->stock;
                $sku   = $variant->sku ?? $product->sku;
                $name  = "{$product->name} - {$variant->name}";
            }

            if ($quantity > $stock) {
                throw ValidationException::withMessages([
                    'items' => ["Only {$stock} unit(s) of '{$name}' are available."],
                ]);
            }

            $lineSubtotal = round($price * $quantity, 2);

            return [
                'product_id'    => $product->id,
                'variant_id'    => $variantId,
                'product'       => $product,
                'variant'       => $variant ?? null,
                'product_name'  => $name,
                'product_sku'   => $sku,
                'product_image' => $image,
                'quantity'      => $quantity,
                'unit_price'    => $price,
                'line_subtotal' => $lineSubtotal,
            ];
        });
    }

    public function calculateShippingCharge(float $subtotal, string $shippingMethod): float
    {
        if ($subtotal > self::FREE_SHIPPING_THRESHOLD) {
            return 0.0;
        }

        return $shippingMethod === 'express'
            ? self::SHIPPING_EXPRESS
            : self::SHIPPING_STANDARD;
    }
}
