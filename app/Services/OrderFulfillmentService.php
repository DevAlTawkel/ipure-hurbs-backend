<?php

namespace App\Services;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Mail;

class OrderFulfillmentService
{
    public function markOrderPaid(Order $order, ?string $chargeId = null): void
    {
        if ($order->payment_status === Order::PAYMENT_PAID) {
            return;
        }

        $order->update([
            'payment_status'   => Order::PAYMENT_PAID,
            'status'           => Order::STATUS_CONFIRMED,
            'stripe_charge_id' => $chargeId ?? $order->stripe_charge_id,
            'paid_at'          => now(),
        ]);

        if (! $order->inventory_decremented) {
            $this->decrementInventory($order);
            $order->update(['inventory_decremented' => true]);
        }

        PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->where('status', PaymentTransaction::STATUS_PENDING)
            ->update(['status' => PaymentTransaction::STATUS_SUCCEEDED]);

        $email = $order->customerEmail();
        if ($email) {
            Mail::to($email)->queue(new OrderPlacedMail($order));
        }
    }

    public function decrementInventory(Order $order): void
    {
        $order->loadMissing(['items.product', 'items.variant']);

        foreach ($order->items as $item) {
            if ($item->variant_id && $item->variant) {
                $item->variant->decrement('stock', $item->qty);
            } elseif ($item->product) {
                $item->product->decrement('stock', $item->qty);
            }
        }
    }
}
