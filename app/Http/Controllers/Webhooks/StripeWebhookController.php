<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $secret  = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature.', 400);
        }

        match ($event->type) {
            'payment_intent.succeeded'               => $this->handlePaymentIntentSucceeded($event->data->object),
            'payment_intent.payment_failed'          => $this->handlePaymentIntentFailed($event->data->object),
            'charge.refunded'                        => $this->handleChargeRefunded($event->data->object),
            default                                  => null,
        };

        return response('OK', 200);
    }

    private function handlePaymentIntentSucceeded(object $paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order && $order->payment_status !== Order::PAYMENT_PAID) {
            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
                'status'         => Order::STATUS_CONFIRMED,
                'stripe_charge_id' => $paymentIntent->latest_charge ?? null,
                'paid_at'        => now(),
            ]);
        }
    }

    private function handlePaymentIntentFailed(object $paymentIntent): void
    {
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($order && $order->payment_status === Order::PAYMENT_PENDING) {
            $order->update(['payment_status' => Order::PAYMENT_FAILED]);
        }
    }

    private function handleChargeRefunded(object $charge): void
    {
        $order = Order::where('stripe_charge_id', $charge->id)->first();

        if ($order) {
            $order->update([
                'payment_status' => Order::PAYMENT_REFUNDED,
                'status'         => Order::STATUS_REFUNDED,
            ]);
        }
    }
}
