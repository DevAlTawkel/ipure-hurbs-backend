<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function letterIndex(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public function scopeByLetter($query, string $letter)
    {
        return $query->where('name', 'like', $letter . '%');
    }

    public function logoUrl(): ?string
    {
        if (blank($this->logo)) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo);
    }
}