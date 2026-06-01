<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
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
                Tabs::make('Product Information')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Select::make('category_id')
                                    ->label('Category')
                                    ->options(Category::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),

                                Select::make('brand_id')
                                    ->label('Brand')
                                    ->options(\App\Models\Brand::query()->pluck('name', 'id'))
                                    ->searchable(),

                                Textarea::make('short_description')
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->columnSpanFull()
                                    ->required(),

                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products'),
                            ]),

                        Tabs\Tab::make('Pricing')
                            ->schema([
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->label('Regular Price (USD)'),

                                TextInput::make('compare_price')
                                    ->label('Compare Price (MRP)')
                                    ->numeric()
                                    ->prefix('$'),

                                TextInput::make('sale_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->label('Sale Price (Optional)'),
                            ]),

                        Tabs\Tab::make('Inventory')
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU (Unique)')
                                    ->maxLength(100)
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('barcode')
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true)
                                    ->label('Barcode (Optional)'),

                                TextInput::make('stock')
                                    ->numeric()
                                    ->required()
                                    ->label('Stock Quantity'),

                                TextInput::make('low_stock_threshold')
                                    ->numeric()
                                    ->default(10)
                                    ->label('Low Stock Threshold')
                                    ->helperText('Alert when stock falls below this level'),

                                Select::make('stock_status')
                                    ->options([
                                        'in_stock' => 'In Stock',
                                        'low_stock' => 'Low Stock',
                                        'out_of_stock' => 'Out of Stock',
                                    ])
                                    ->required(),
                            ]),

                        Tabs\Tab::make('SEO & Settings')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->maxLength(255)
                                    ->label('SEO Title'),

                                Textarea::make('seo_description')
                                    ->maxLength(500)
                                    ->label('SEO Description'),

                                Toggle::make('is_active')
                                    ->default(true),

                                Toggle::make('is_featured')
                                    ->label('Featured Product'),

                                Toggle::make('is_trending')
                                    ->label('Trending Product'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
