<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'order_number' => $this->order_number,
            'status'       => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,

            'shipping' => [
                'name'    => $this->shipping_name,
                'phone'   => $this->shipping_phone,
                'line1'   => $this->shipping_line1,
                'line2'   => $this->shipping_line2,
                'city'    => $this->shipping_city,
                'state'   => $this->shipping_state,
                'country' => $this->shipping_country,
                'pincode' => $this->shipping_pincode,
            ],

            'pricing' => [
                'subtotal'        => $this->subtotal,
                'discount_amount' => $this->discount_amount,
                'discount_reason' => $this->discount_reason,
                'shipping_charge' => $this->shipping_charge,
                'total'           => $this->total,
            ],

            'items'      => OrderItemResource::collection($this->whenLoaded('items')),
            'notes'      => $this->notes,
            'paid_at'    => $this->paid_at?->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
