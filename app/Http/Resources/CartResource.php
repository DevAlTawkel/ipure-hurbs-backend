<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'cart_token'  => $this->session_token,
            'item_count'  => $this->itemCount(),
            'total'       => $this->total(),
            'items'       => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
