<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockWidget extends TableWidget
{
    protected static ?string $heading = 'Low Stock & Out of Stock Products';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stock', '<=', 10)
                    ->where('is_active', true)
                    ->orderBy('stock')
            )
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('sku')->label('SKU'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('stock')
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'warning')
                    ->formatStateUsing(fn (int $state): string => $state === 0 ? 'Out of Stock' : "{$state} left"),
            ])
            ->paginated([5, 10]);
    }
}
