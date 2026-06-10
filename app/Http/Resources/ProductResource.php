<?php

namespace App\Http\Resources;

use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isDetail = $this->relationLoaded('variants') || $this->relationLoaded('sections');

        // Currency: X-Currency header OR ?currency= param, default USD
        $code = strtoupper(
            $request->header('X-Currency') ?? $request->query('currency', 'USD')
        );
        $fx = app(CurrencyService::class);

        // Resolve to a known currency (fallback to USD if unknown code sent)
        $currencyInfo = $fx->forCode($code);
        $code         = $currencyInfo['code'];

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

            // ── Pricing (converted to requested currency) ─────────────────
            'currency'            => $code,
            'currency_symbol'     => $currencyInfo['symbol'],
            'price_usd'           => (float) $this->price,          // always USD, used for Stripe checkout
            'price'               => $fx->convert((float) $this->price, $code),
            'compare_price'       => $this->compare_price
                                        ? $fx->convert((float) $this->compare_price, $code) : null,
            'sale_price'          => $this->sale_price
                                        ? $fx->convert((float) $this->sale_price, $code) : null,
            'formatted_price'     => $fx->format((float) $this->price, $code),
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
            'images'              => collect($this->gallery ?? [])
                ->values()
                ->map(fn ($path, $index) => [
                    'id'  => $index + 1,
                    'url' => \Illuminate\Support\Facades\Storage::disk('public')->url($path),
                ])
                ->values(),

            // ── Additional Info ──────────────────────────────────────────
            'additional_info'     => [
                'key_herbal_ingredients' => $this->key_herbal_ingredients ?? [],
                'key_benefits'           => $this->key_benefits ?? [],
                'specifications'         => $this->specifications ?? [],
                'indications'            => $this->indications ?? [],
                'allergen_info'          => $this->allergen_info,
                'other_ingredients'      => $this->other_ingredients,
            ],

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
            'variants'            => $this->whenLoaded('variants', fn () =>
                $this->variants->map(fn ($v) => [
                    'variant_id'          => $v->id,
                    'name'                => $v->name,
                    'sku'                 => $v->sku,
                    'price_usd'           => (float) $v->price,
                    'price'               => $fx->convert((float) $v->price, $code),
                    'compare_price'       => $v->compare_price
                                                ? $fx->convert((float) $v->compare_price, $code) : null,
                    'sale_price'          => $v->sale_price
                                                ? $fx->convert((float) $v->sale_price, $code) : null,
                    'effective_price'     => $fx->convert((float) ($v->sale_price ?? $v->price), $code),
                    'formatted_price'     => $fx->format((float) ($v->sale_price ?? $v->price), $code),
                    'has_discount'        => $v->hasDiscount(),
                    'discount_percentage' => $v->discountPercentage(),
                    'stock'               => (int) $v->stock,
                    'in_stock'            => $v->inStock(),
                    'is_default'          => (bool) $v->is_default,
                ])->values()
            ),

            // ── Content Sections ──────────────────────────────────────────
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
                'average' => (float) $this->rating,
                'total'   => (int) $this->review_count,
                'data'    => $this->reviews->map(fn ($r) => [
                    'id'                   => $r->id,
                    'rating'               => (int) $r->rating,
                    'title'                => $r->title,
                    'body'                 => $r->body,
                    'is_verified_purchase' => (bool) $r->is_verified_purchase,
                    'reviewer_name'        => $r->customer?->name ?? 'Anonymous',
                    'created_at'           => $r->created_at->toDateString(),
                ])->values(),
            ]),

            // ── Related Products ─────────────────────────────────────────
            'related_products'    => $this->whenLoaded('related', fn () =>
                $this->related->map(fn ($p) => [
                    'id'                  => $p->id,
                    'name'                => $p->name,
                    'slug'                => $p->slug,
                    'price_usd'           => (float) $p->price,
                    'price'               => $fx->convert((float) $p->price, $code),
                    'compare_price'       => $p->compare_price
                                                ? $fx->convert((float) $p->compare_price, $code) : null,
                    'sale_price'          => $p->sale_price
                                                ? $fx->convert((float) $p->sale_price, $code) : null,
                    'formatted_price'     => $fx->format((float) $p->price, $code),
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
