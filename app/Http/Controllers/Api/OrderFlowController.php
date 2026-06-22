<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Services\OrderCalculationService;
use App\Services\StripePaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderFlowController extends Controller
{
    public function __construct(
        private readonly OrderCalculationService $orderCalculation,
        private readonly StripePaymentService $stripePayment,
    ) {}

    /**
     * POST /api/order/calculate
     * Preview order totals for Buy Now / checkout.
     */
    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.product_id'       => ['required', 'integer', 'exists:products,id'],
            'items.*.variant_id'       => ['nullable', 'integer', 'exists:product_variants,id'],
            'items.*.quantity'         => ['required', 'integer', 'min:1', 'max:100'],
            'shipping_method'          => ['required', 'in:standard,express'],
            'promo_code'               => ['nullable', 'string', 'max:50'],
        ]);

        $summary = $this->orderCalculation->calculate(
            $data['items'],
            $data['shipping_method'],
            $data['promo_code'] ?? null,
        );

        return response()->json([
            'status'            => true,
            'subtotal'          => $summary['subtotal'],
            'shipping_method'   => $summary['shipping_method'],
            'shipping_charge'   => $summary['shipping_charge'],
            'discount'          => $summary['discount'],
            'grand_total'       => $summary['grand_total'],
            'is_free_shipping'  => $summary['is_free_shipping'],
        ]);
    }

    /**
     * POST /api/order/create
     * Persist order and initiate payment.
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items'                              => ['required', 'array', 'min:1'],
            'items.*.product_id'                 => ['required', 'integer', 'exists:products,id'],
            'items.*.variant_id'                 => ['nullable', 'integer', 'exists:product_variants,id'],
            'items.*.quantity'                   => ['required', 'integer', 'min:1', 'max:100'],
            'shipping_method'                    => ['required', 'in:standard,express'],
            'shipping_charge'                    => ['required', 'numeric', 'min:0'],
            'subtotal'                           => ['required', 'numeric', 'min:0'],
            'grand_total'                        => ['required', 'numeric', 'min:0'],
            'promo_code'                         => ['nullable', 'string', 'max:50'],
            'shipping_address'                   => ['required', 'array'],
            'shipping_address.name'              => ['required', 'string', 'max:255'],
            'shipping_address.email'             => ['required', 'email', 'max:255'],
            'shipping_address.phone'             => ['required', 'string', 'max:30'],
            'shipping_address.address'           => ['required', 'string', 'max:500'],
            'shipping_address.city'              => ['required', 'string', 'max:100'],
            'shipping_address.country'           => ['required', 'string', 'max:100'],
            'shipping_address.state'             => ['nullable', 'string', 'max:100'],
            'shipping_address.pincode'           => ['nullable', 'string', 'max:20'],
            'billing_address'                    => ['nullable', 'array'],
            'notes'                              => ['nullable', 'string', 'max:500'],
        ]);

        $summary = $this->orderCalculation->calculate(
            $data['items'],
            $data['shipping_method'],
            $data['promo_code'] ?? null,
        );

        $this->assertTotalsMatch($summary, $data);

        $customer = $request->user('customer');
        $address  = $data['shipping_address'];

        $order = DB::transaction(function () use ($data, $summary, $customer, $address, $request) {
            $order = Order::create([
                'customer_id'       => $customer?->id,
                'guest_email'       => $customer ? null : $address['email'],
                'guest_name'        => $customer ? null : $address['name'],
                'guest_phone'       => $customer ? null : $address['phone'],
                'shipping_name'     => $address['name'],
                'shipping_phone'    => $address['phone'],
                'shipping_line1'    => $address['address'],
                'shipping_line2'    => null,
                'shipping_city'     => $address['city'],
                'shipping_state'    => $address['state'] ?? $address['city'],
                'shipping_country'  => $this->normalizeCountry($address['country']),
                'shipping_pincode'  => $address['pincode'] ?? '00000',
                'subtotal'          => $summary['subtotal'],
                'discount_amount'   => $summary['discount'],
                'discount_reason'   => $summary['discount_reason'],
                'shipping_charge'   => $summary['shipping_charge'],
                'shipping_method'   => $summary['shipping_method'],
                'total'             => $summary['grand_total'],
                'status'            => Order::STATUS_PENDING,
                'payment_method'    => 'stripe',
                'payment_status'    => Order::PAYMENT_PENDING,
                'notes'             => $request->input('notes'),
            ]);

            foreach ($summary['items'] as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'variant_id'    => $item['variant_id'],
                    'product_name'  => $item['product_name'],
                    'product_sku'   => $item['product_sku'],
                    'variant_name'  => $item['variant']?->name,
                    'product_image' => $item['product_image'],
                    'qty'           => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'subtotal'      => $item['line_subtotal'],
                ]);
            }

            return $order;
        });

        $payment = $this->stripePayment->createPaymentIntent(
            $order,
            $address['email'],
        );

        $order->update(['stripe_payment_intent_id' => $payment['payment_intent_id']]);

        PaymentTransaction::create([
            'order_id'         => $order->id,
            'transaction_id'   => $payment['payment_intent_id'],
            'gateway'          => 'stripe',
            'amount'           => $order->total,
            'currency'         => 'USD',
            'status'           => PaymentTransaction::STATUS_PENDING,
            'gateway_response' => [
                'client_secret' => $payment['client_secret'],
                'payment_url'   => $payment['payment_url'],
            ],
        ]);

        return response()->json([
            'status'       => true,
            'order_id'     => $order->order_number,
            'payment_url'  => $payment['payment_url'],
            'client_secret'=> $payment['client_secret'],
        ], 201);
    }

    /**
     * @param  array<string, mixed>  $summary
     * @param  array<string, mixed>  $data
     */
    private function assertTotalsMatch(array $summary, array $data): void
    {
        $mismatch = abs($summary['subtotal'] - (float) $data['subtotal']) > 0.01
            || abs($summary['shipping_charge'] - (float) $data['shipping_charge']) > 0.01
            || abs($summary['grand_total'] - (float) $data['grand_total']) > 0.01;

        if ($mismatch) {
            throw ValidationException::withMessages([
                'grand_total' => ['Order totals have changed. Please recalculate before continuing.'],
            ]);
        }
    }

    private function normalizeCountry(string $country): string
    {
        $normalized = strtoupper(trim($country));

        $map = [
            'UAE'                  => 'AE',
            'UNITED ARAB EMIRATES' => 'AE',
            'INDIA'                => 'IN',
            'UNITED STATES'        => 'US',
            'USA'                  => 'US',
        ];

        if (strlen($normalized) === 2) {
            return $normalized;
        }

        return $map[$normalized] ?? substr($normalized, 0, 2);
    }
}
