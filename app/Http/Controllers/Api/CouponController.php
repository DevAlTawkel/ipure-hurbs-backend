<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate and apply coupon
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'cart_subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon code not found.',
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon is expired or inactive.',
            ], 400);
        }

        if ($coupon->minimum_spend && $request->cart_subtotal < $coupon->minimum_spend) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum order amount of \${$coupon->minimum_spend} required.",
            ], 400);
        }

        $discount = $coupon->calculateDiscount($request->cart_subtotal);

        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'discount_amount' => round($discount, 2),
            ],
            'message' => 'Coupon applied successfully!',
        ]);
    }

    /**
     * Get coupon details
     */
    public function show(string $code): JsonResponse
    {
        $coupon = Coupon::where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'code' => $coupon->code,
                'description' => $coupon->description,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'minimum_spend' => $coupon->minimum_spend,
                'valid_until' => $coupon->valid_until,
            ],
        ]);
    }
}
