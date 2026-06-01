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
            'barcode'             => $this->barcode,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'short_description'   => $this->short_description,
            'description'         => $this->description,
            'price'               => (float) $this->price,
            'compare_price'       => $this->compare_price ? (float) $this->compare_price : null,
            'sale_price'          => $this->sale_price ? (float) $this->sale_price : null,
            'formatted_price'     => $this->formattedPrice(),
            'has_discount'        => $this->hasDiscount(),
            'discount_percentage' => $this->discountPercentage(),
            'rating'              => (float) $this->rating,
            'review_count'        => (int) $this->review_count,
            'reviews_count'       => (int) $this->review_count,
            'sales_count'         => (int) $this->sales_count,
            'stock'               => (int) $this->stock,
            'low_stock_threshold' => (int) $this->low_stock_threshold,
            'stock_status'        => $this->stock_status,
            'in_stock'            => $this->inStock(),
            'seo_title'           => $this->seo_title,
            'seo_description'     => $this->seo_description,
            'image_url'           => $this->imageUrl(),
            'images'              => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'id'         => $img->id,
                'url'        => $img->url(),
                'is_primary' => $img->is_primary,
                'sort_order' => $img->sort_order,
            ])),
            'is_active'    => (bool) $this->is_active,
            'is_featured'  => (bool) $this->is_featured,
            'is_trending'  => (bool) $this->is_trending,
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
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}