<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::query()->pluck('name', 'id'))
                    ->searchable(),

                Select::make('brand_id')
                    ->label('Brand')
                    ->options(\App\Models\Brand::query()->pluck('name', 'id'))
                    ->searchable(),

                TextInput::make('short_description')
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('₹'),

                TextInput::make('compare_price')
                    ->label('Compare Price (MRP)')
                    ->numeric()
                    ->prefix('₹'),

                TextInput::make('stock')
                    ->numeric()
                    ->required(),

                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('products'),

                Toggle::make('is_active')
                    ->default(true),

                Toggle::make('is_featured'),

                Toggle::make('is_trending'),
            ]);
    }
}
