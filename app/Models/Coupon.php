<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_spend',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'minimum_spend'  => 'decimal:2',
            'usage_limit'    => 'integer',
            'usage_count'    => 'integer',
            'valid_from'     => 'datetime',
            'valid_until'    => 'datetime',
            'is_active'      => 'boolean',
        ];
    }

    public function isValid(): bool
    {
        return $this->is_active
            && now()->between($this->valid_from, $this->valid_until ?? now()->addYears(10))
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid() || ($this->minimum_spend && $amount < $this->minimum_spend)) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return ($amount * $this->discount_value) / 100;
        }

        return min($this->discount_value, $amount);
    }
}
