<?php

namespace App\Http\Resources;

use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'sku'                 => $this->sku,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'short_description'   => $this->short_description,
            'description'         => $this->description,
            'price'               => $this->price,
            'compare_price'       => $this->compare_price,
            'formatted_price'     => $this->formattedPrice(),
            'has_discount'        => $this->hasDiscount(),
            'discount_percentage' => $this->discountPercentage(),
            'rating'              => (float) $this->rating,
            'review_count'        => $this->review_count,
            'stock'               => $this->stock,
            'in_stock'            => $this->inStock(),
            'image_url'           => $this->imageUrl(),
            'images'              => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'id'         => $img->id,
                'url'        => $img->url(),
                'is_primary' => $img->is_primary,
                'sort_order' => $img->sort_order,
            ])),
            'is_featured'  => $this->is_featured,
            'is_trending'  => $this->is_trending,
            'category'     => $this->whenLoaded('category', fn () => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand ? [
                'id'   => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ] : null),
            'reviews' => $this->whenLoaded('reviews', fn () => ReviewResource::collection($this->reviews)),
        ];
    }
}