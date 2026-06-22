<?php

namespace App\Services;

use App\Models\Order;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentService
{
    /**
     * @return array{payment_intent_id: string, client_secret: string, payment_url: string}
     */
    public function createPaymentIntent(Order $order, string $customerEmail): array
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'                    => (int) round($order->total * 100),
            'currency'                  => 'usd',
            'receipt_email'             => $customerEmail,
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => [
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);

        $paymentUrl = rtrim(config('app.frontend_url'), '/')
            . '/checkout/payment'
            . '?order_id=' . urlencode($order->order_number)
            . '&payment_intent=' . urlencode($intent->id);

        return [
            'payment_intent_id' => $intent->id,
            'client_secret'     => $intent->client_secret,
            'payment_url'       => $paymentUrl,
        ];
    }
}
