<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'name'      => 'Test Herb',
            'price'     => 60.00,
            'stock'     => 50,
            'is_active' => true,
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'name'       => '60 Capsules',
            'price'      => 60.00,
            'stock'      => 20,
            'is_active'  => true,
        ]);
    }

    public function test_calculate_returns_free_shipping_when_subtotal_exceeds_threshold(): void
    {
        $response = $this->postJson('/api/order/calculate', [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'variant_id' => $this->variant->id,
                    'quantity'   => 2,
                ],
            ],
            'shipping_method' => 'express',
            'promo_code'      => null,
        ]);

        $response->assertOk()
            ->assertJson([
                'status'           => true,
                'subtotal'         => 120,
                'shipping_method'  => 'express',
                'shipping_charge'  => 0,
                'discount'         => 0,
                'grand_total'      => 120,
                'is_free_shipping' => true,
            ]);
    }

    public function test_calculate_applies_standard_shipping_under_threshold(): void
    {
        $response = $this->postJson('/api/order/calculate', [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'variant_id' => $this->variant->id,
                    'quantity'   => 1,
                ],
            ],
            'shipping_method' => 'standard',
            'promo_code'      => null,
        ]);

        $response->assertOk()
            ->assertJson([
                'status'           => true,
                'subtotal'         => 60,
                'shipping_charge'  => 30,
                'grand_total'      => 90,
                'is_free_shipping' => false,
            ]);
    }

    public function test_calculate_applies_promo_code_discount(): void
    {
        Coupon::create([
            'code'           => 'SAVE10',
            'description'    => 'Ten percent off',
            'discount_type'  => 'percentage',
            'discount_value' => 10,
            'usage_count'    => 0,
            'valid_from'     => now()->subDay(),
            'valid_until'    => now()->addMonth(),
            'is_active'      => true,
        ]);

        $response = $this->postJson('/api/order/calculate', [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'variant_id' => $this->variant->id,
                    'quantity'   => 2,
                ],
            ],
            'shipping_method' => 'express',
            'promo_code'      => 'SAVE10',
        ]);

        $response->assertOk()
            ->assertJson([
                'subtotal'    => 120,
                'discount'    => 12,
                'grand_total' => 108,
            ]);
    }

    public function test_create_persists_order_and_returns_payment_url(): void
    {
        $payload = [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'variant_id' => $this->variant->id,
                    'quantity'   => 2,
                ],
            ],
            'shipping_method'  => 'express',
            'shipping_charge'  => 0,
            'subtotal'         => 120,
            'grand_total'      => 120,
            'shipping_address' => [
                'name'    => 'John Doe',
                'email'   => 'john@example.com',
                'phone'   => '+97150000000',
                'address' => 'Dubai Marina',
                'city'    => 'Dubai',
                'country' => 'UAE',
            ],
        ];

        $response = $this->postJson('/api/order/create', $payload);

        $response->assertCreated()
            ->assertJson([
                'status'      => true,
                'order_id'    => Order::first()->order_number,
                'payment_url' => 'https://ipureherbs.org/checkout/payment?order_id='
                    . Order::first()->order_number
                    . '&payment_intent=pi_test_123',
            ]);

        $this->assertDatabaseHas('orders', [
            'guest_email'     => 'john@example.com',
            'shipping_method' => 'express',
            'shipping_charge' => 0,
            'subtotal'        => 120,
            'total'           => 120,
            'status'          => Order::STATUS_PENDING,
            'payment_status'  => Order::PAYMENT_PENDING,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'variant_id' => $this->variant->id,
            'qty'        => 2,
        ]);

        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => 'pi_test_123',
            'amount'         => 120,
            'status'         => 'pending',
        ]);
    }

    public function test_create_rejects_mismatched_totals(): void
    {
        $response = $this->postJson('/api/order/create', [
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'variant_id' => $this->variant->id,
                    'quantity'   => 1,
                ],
            ],
            'shipping_method'  => 'standard',
            'shipping_charge'  => 0,
            'subtotal'         => 60,
            'grand_total'      => 60,
            'shipping_address' => [
                'name'    => 'John Doe',
                'email'   => 'john@example.com',
                'phone'   => '+97150000000',
                'address' => 'Dubai Marina',
                'city'    => 'Dubai',
                'country' => 'UAE',
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['grand_total']);
    }
}
