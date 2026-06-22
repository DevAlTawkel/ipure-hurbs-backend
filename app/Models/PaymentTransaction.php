<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    public const STATUS_PENDING   = 'pending';

    public const STATUS_SUCCEEDED = 'succeeded';

    public const STATUS_FAILED    = 'failed';

    protected $fillable = [
        'order_id',
        'transaction_id',
        'gateway',
        'amount',
        'currency',
        'status',
        'gateway_response',
    ];

    protected function casts(): array
    {
        return [
            'amount'           => 'decimal:2',
            'gateway_response' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
