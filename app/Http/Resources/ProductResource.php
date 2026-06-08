<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isDetail = $this->relationLoaded('variants') || $this->relationLoaded('sections');

        return [
            // ── Identity ──────────────────────────────────────────────────
            'id'                  => $this->id,
            'sku'                 => $this->sku,
            'barcode'             => $this->barcode,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'tags'                => $this->tags ?? [],

            // ── Descriptions ─────────────────────────────────────────────
            'short_description'   => $this->short_description,
            'description'         => $this->description,

            // ── Pricing ──────────────────────────────────────────────────
            'price'               => (float) $this->price,
            'compare_price'       => $this->compare_price ? (float) $this->compare_price : null,
            'sale_price'          => $this->sale_price   ? (float) $this->sale_price   : null,
            'formatted_price'     => $this->formattedPrice(),
            'has_discount'        => $this->hasDiscount(),
            'discount_percentage' => $this->discountPercentage(),

            // ── Ratings & Sales ──────────────────────────────────────────
            'rating'              => (float) $this->rating,
            'review_count'        => (int) $this->review_count,
            'sales_count'         => (int) $this->sales_count,

            // ── Stock ────────────────────────────────────────────────────
            'stock'               => (int) $this->stock,
            'stock_status'        => $this->stock_status,
            'in_stock'            => $this->inStock(),

            // ── Flags ────────────────────────────────────────────────────
            'is_active'           => (bool) $this->is_active,
            'is_featured'         => (bool) $this->is_featured,
            'is_trending'         => (bool) $this->is_trending,

            // ── Media ────────────────────────────────────────────────────
            'image_url'           => $this->imageUrl(),
            'images'              => $this->whenLoaded('images', fn () =>
                $this->images->map(fn ($img) => [
                    'id'         => $img->id,
                    'url'        => $img->url(),
                    'is_primary' => (bool) $img->is_primary,
                    'sort_order' => $img->sort_order,
                ])
            ),
            ...($this->relationLoaded('images')
                ? $this->images->sortBy('sort_order')->values()
                    ->mapWithKeys(fn ($img, $i) => ['image' . ($i + 1) => $img->url()])
                    ->toArray()
                : []),

            // ── Category & Brand ─────────────────────────────────────────
            'category'            => $this->whenLoaded('category', fn () => $this->category ? [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null),
            'brand'               => $this->whenLoaded('brand', fn () => $this->brand ? [
                'id'      => $this->brand->id,
                'name'    => $this->brand->name,
                'slug'    => $this->brand->slug,
                'logo'    => $this->brand->logoUrl() ?? null,
            ] : null),

            // ── Size / Price Variants ────────────────────────────────────
            // Only included on the detail page response (when variants are loaded)
            'variants'            => $this->whenLoaded('variants', fn () =>
                $this->variants->map(fn ($v) => [
                    'id'                  => $v->id,
                    'name'                => $v->name,
                    'sku'                 => $v->sku,
                    'price'               => (float) $v->price,
                    'compare_price'       => $v->compare_price ? (float) $v->compare_price : null,
                    'sale_price'          => $v->sale_price    ? (float) $v->sale_price    : null,
                    'formatted_price'     => '$' . number_format((float) $v->price, 2),
                    'has_discount'        => $v->hasDiscount(),
                    'discount_percentage' => $v->discountPercentage(),
                    'stock'               => (int) $v->stock,
                    'in_stock'            => $v->inStock(),
                    'is_default'          => (bool) $v->is_default,
                ])
            ),

            // ── 9 Content Sections ────────────────────────────────────────
            // Matches the product detail page tabs:
            // Description · Key Benefits · Active Ingredients ·
            // Natural Essential Benefits · Application · FDA Regulations ·
            // Suggested Use · Supplement Facts · Allergen & Warnings
            'sections'            => $this->whenLoaded('sections', fn () =>
                $this->sections->map(fn ($s) => [
                    'id'           => $s->id,
                    'heading'      => $s->heading,
                    'content'      => $s->content,
                    'content_type' => $s->content_type,
                    'sort_order'   => $s->sort_order,
                ])
            ),

            // ── Reviews ──────────────────────────────────────────────────
            'reviews'             => $this->whenLoaded('reviews', fn () => [
                'average'    => (float) $this->rating,
                'total'      => (int) $this->review_count,
                'items'      => $this->reviews->map(fn ($r) => [
                    'id'                   => $r->id,
                    'rating'               => (int) $r->rating,
                    'title'                => $r->title,
                    'body'                 => $r->body,
                    'is_verified_purchase' => (bool) $r->is_verified_purchase,
                    'reviewer_name'        => $r->customer?->name ?? 'Anonymous',
                    'created_at'           => $r->created_at->toDateString(),
                ]),
            ]),

            // ── Related / Recommended Products ───────────────────────────
            'related_products'    => $this->whenLoaded('related', fn () =>
                $this->related->map(fn ($p) => [
                    'id'                  => $p->id,
                    'name'                => $p->name,
                    'slug'                => $p->slug,
                    'price'               => (float) $p->price,
                    'compare_price'       => $p->compare_price ? (float) $p->compare_price : null,
                    'sale_price'          => $p->sale_price    ? (float) $p->sale_price    : null,
                    'formatted_price'     => '$' . number_format((float) $p->price, 2),
                    'has_discount'        => $p->hasDiscount(),
                    'discount_percentage' => $p->discountPercentage(),
                    'rating'              => (float) $p->rating,
                    'review_count'        => (int) $p->review_count,
                    'image_url'           => $p->imageUrl(),
                    'in_stock'            => $p->inStock(),
                    'tags'                => $p->tags ?? [],
                    'category'            => $p->category ? [
                        'id'   => $p->category->id,
                        'name' => $p->category->name,
                        'slug' => $p->category->slug,
                    ] : null,
                ])
            ),

            // ── SEO ──────────────────────────────────────────────────────
            'seo'                 => [
                'title'       => $this->seo_title,
                'description' => $this->seo_description,
            ],

            'created_at'          => $this->created_at?->toIso8601String(),
            'updated_at'          => $this->updated_at?->toIso8601String(),
        ];
    }
}
