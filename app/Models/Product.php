<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductVariant;
use App\Models\ProductSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'barcode',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'compare_price',
        'sale_price',
        'rating',
        'review_count',
        'stock',
        'low_stock_threshold',
        'stock_status',
        'sales_count',
        'image',
        'is_active',
        'is_featured',
        'is_trending',
        'seo_title',
        'seo_description',
        'tags',
        'gallery',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'decimal:2',
            'compare_price' => 'decimal:2',
            'sale_price'    => 'decimal:2',
            'rating'        => 'decimal:1',
            'review_count'  => 'integer',
            'stock'         => 'integer',
            'low_stock_threshold' => 'integer',
            'sales_count'   => 'integer',
            'is_active'     => 'boolean',
            'is_featured'   => 'boolean',
            'is_trending'   => 'boolean',
            'tags'          => 'array',
            'gallery'       => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug) && filled($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function brand(): BelongsTo
{
    return $this->belongsTo(Brand::class);
}

public function images(): HasMany
{
    return $this->hasMany(ProductImage::class)->orderBy('sort_order');
}

public function reviews(): HasMany
{
    return $this->hasMany(Review::class)->where('is_approved', true);
}

public function wishlists(): HasMany
{
    return $this->hasMany(Wishlist::class);
}

public function stockMovements(): HasMany
{
    return $this->hasMany(StockMovement::class)->orderByDesc('created_at');
}

public function variants(): HasMany
{
    return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
}

public function sections(): HasMany
{
    return $this->hasMany(ProductSection::class)->where('is_active', true)->orderBy('sort_order');
}

    public function imageUrl(): ?string
    {
        if (blank($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function formattedPrice(): string
    {
        return '$'.number_format((float) $this->price, 2);
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public function hasDiscount(): bool
    {
        return filled($this->compare_price) && $this->compare_price > $this->price;
    }

    public function discountPercentage(): int
    {
        if (! $this->hasDiscount()) {
            return 0;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
