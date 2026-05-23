<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'product_name'  => $this->product_name,
            'product_sku'   => $this->product_sku,
            'product_image' => $this->product_image
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->product_image)
                : null,
            'qty'           => $this->qty,
            'unit_price'    => $this->unit_price,
            'subtotal'      => $this->subtotal,
        ];
    }
}
