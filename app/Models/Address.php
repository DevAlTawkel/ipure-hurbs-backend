<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'line1',
        'line2',
        'city',
        'state',
        'country',
        'pincode',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function toShippingArray(): array
    {
        return [
            'shipping_name'    => $this->name,
            'shipping_phone'   => $this->phone,
            'shipping_line1'   => $this->line1,
            'shipping_line2'   => $this->line2,
            'shipping_city'    => $this->city,
            'shipping_state'   => $this->state,
            'shipping_country' => $this->country,
            'shipping_pincode' => $this->pincode,
        ];
    }
}
