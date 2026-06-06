<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'reference',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'created_by' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function record(Product $product, string $type, int $quantity, ?string $reference = null, ?string $notes = null, ?int $userId = null): self
    {
        return self::create([
            'product_id' => $product->id,
            'movement_type' => $type,
            'quantity' => $quantity,
            'reference' => $reference,
            'notes' => $notes,
            'created_by' => $userId,
        ]);
    }
}
