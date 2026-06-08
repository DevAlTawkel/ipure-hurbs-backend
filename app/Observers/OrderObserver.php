<?php

namespace App\Observers;

use App\Mail\OrderDeliveredMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        $email = $order->customer?->email ?? $order->guest_email;
        if (! $email) {
            return;
        }

        match ($order->status) {
            'shipped'   => Mail::to($email)->queue(new OrderShippedMail($order)),
            'delivered' => Mail::to($email)->queue(new OrderDeliveredMail($order)),
            default     => null,
        };
    }
}
