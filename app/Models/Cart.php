<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'session_token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function total(): float
    {
        return (float) $this->items->sum(fn (CartItem $item) => $item->price_at_add * $item->qty);
    }

    public function itemCount(): int
    {
        return (int) $this->items->sum('qty');
    }

    public static function findOrCreateForRequest(Request $request): self
    {
        $customer = $request->user('customer');

        if ($customer) {
            return static::firstOrCreate(['customer_id' => $customer->id], [
                'expires_at' => now()->addDays(30),
            ]);
        }

        $token = $request->header('X-Cart-Token') ?? $request->input('cart_token');

        if ($token) {
            $cart = static::where('session_token', $token)
                ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
                ->first();

            if ($cart) {
                return $cart;
            }
        }

        return static::create([
            'session_token' => Str::random(64),
            'expires_at'    => now()->addDays(7),
        ]);
    }

    public function mergeIntoCustomerCart(Customer $customer): self
    {
        $customerCart = static::firstOrCreate(['customer_id' => $customer->id], [
            'expires_at' => now()->addDays(30),
        ]);

        foreach ($this->items as $item) {
            $existing = $customerCart->items()->where('product_id', $item->product_id)->first();

            if ($existing) {
                $existing->increment('qty', $item->qty);
            } else {
                $customerCart->items()->create([
                    'product_id'   => $item->product_id,
                    'qty'          => $item->qty,
                    'price_at_add' => $item->price_at_add,
                ]);
            }
        }

        $this->delete();

        return $customerCart;
    }
}
