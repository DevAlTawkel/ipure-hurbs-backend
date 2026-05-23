<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'logo_url'     => $this->logo
                ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo)
                : null,
            'website'      => $this->website,
            'letter_index' => $this->letter_index,
            'product_count' => $this->whenCounted('products'),
        ];
    }
}