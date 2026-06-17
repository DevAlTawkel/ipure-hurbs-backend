<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    const LOW_STOCK_THRESHOLD = 10;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('price')
                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2))
                    ->sortable(),

                // Stock column with colour coding
                TextColumn::make('stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0      => 'danger',
                        $state <= self::LOW_STOCK_THRESHOLD => 'warning',
                        default           => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => match (true) {
                        $state === 0      => "Out of Stock",
                        $state <= self::LOW_STOCK_THRESHOLD => "{$state} (Low)",
                        default           => (string) $state,
                    }),

                TextColumn::make('category.name')->label('Category'),
                ToggleColumn::make('is_active')->label('Published'),
                IconColumn::make('is_featured')->boolean(),
            ])
            ->filters([
                // Stock status filter
                SelectFilter::make('stock_status')
                    ->label('Stock Status')
                    ->options([
                        'out_of_stock' => 'Out of Stock',
                        'low_stock'    => 'Low Stock (≤ 10)',
                        'in_stock'     => 'In Stock',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => match ($data['value'] ?? null) {
                        'out_of_stock' => $query->where('stock', 0),
                        'low_stock'    => $query->where('stock', '>', 0)->where('stock', '<=', self::LOW_STOCK_THRESHOLD),
                        'in_stock'     => $query->where('stock', '>', self::LOW_STOCK_THRESHOLD),
                        default        => $query,
                    }),

                // Active/Inactive filter
                TernaryFilter::make('is_active')->label('Active'),

                // Featured filter
                TernaryFilter::make('is_featured')->label('Featured'),

                // Category filter
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                // Inline stock adjustment
                Action::make('adjustStock')
                    ->label('Adjust Stock')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('warning')
                    ->form([
                        TextInput::make('stock')
                            ->label('New Stock Quantity')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                    ])
                    ->fillForm(fn ($record): array => ['stock' => $record->stock])
                    ->action(fn ($record, array $data) => $record->update(['stock' => (int) $data['stock']])),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
