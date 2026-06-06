<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'compare_price',
        'sale_price',
        'stock',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'decimal:2',
            'compare_price' => 'decimal:2',
            'sale_price'    => 'decimal:2',
            'stock'         => 'integer',
            'is_default'    => 'boolean',
            'is_active'     => 'boolean',
            'sort_order'    => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
}
