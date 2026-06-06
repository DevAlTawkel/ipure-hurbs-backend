<?php

namespace App\Filament\Resources\Wishlists;

use App\Models\Wishlist;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class WishlistResource extends Resource
{
    protected static ?string $model = Wishlist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineHeart;

    protected static ?string $navigationLabel = 'Wishlists';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('customer_id')
                ->label('Customer')
                ->relationship('customer', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.price')
                    ->money('usd')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('customer')
                    ->relationship('customer', 'name'),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWishlists::route('/'),
        ];
    }
}
