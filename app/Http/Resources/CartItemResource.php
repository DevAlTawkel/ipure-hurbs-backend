<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'qty'          => $this->qty,
            'price_at_add' => $this->price_at_add,
            'subtotal'     => $this->subtotal(),
            'product'      => $this->whenLoaded('product', fn () => [
                'id'        => $this->product->id,
                'name'      => $this->product->name,
                'slug'      => $this->product->slug,
                'sku'       => $this->product->sku,
                'image_url' => $this->product->imageUrl(),
                'price'     => $this->product->price,
                'in_stock'  => $this->product->inStock(),
                'stock'     => $this->product->stock,
            ]),
            'variant'      => $this->whenLoaded('variant', fn () => $this->variant ? [
                'id'       => $this->variant->id,
                'name'     => $this->variant->name,
                'sku'      => $this->variant->sku,
                'price'    => (float) $this->variant->price,
                'in_stock' => $this->variant->inStock(),
                'stock'    => $this->variant->stock,
            ] : null),
        ];
    }
}
