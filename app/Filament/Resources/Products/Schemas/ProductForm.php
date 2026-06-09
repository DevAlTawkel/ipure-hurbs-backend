<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
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

                                TagsInput::make('tags')
                                    ->label('Tags')
                                    ->suggestions(['Deal', 'Best Seller', 'New Arrival', 'Hot', 'Sale', 'Top Rated', 'Limited'])
                                    ->helperText('Press Enter to add a tag. Examples: Deal, Best Seller, New Arrival')
                                    ->columnSpanFull(),

                                FileUpload::make('image')
                                    ->label('Main Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products'),

                                FileUpload::make('gallery')
                                    ->label('Gallery Images (up to 4)')
                                    ->image()
                                    ->multiple()
                                    ->maxFiles(4)
                                    ->disk('public')
                                    ->directory('products/gallery')
                                    ->reorderable()
                                    ->helperText('Upload up to 4 product images. These appear in the product image gallery.'),
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

                        Tabs\Tab::make('Size Variants')
                            ->schema([
                                Repeater::make('variants')
                                    ->label('Size / Price Variants')
                                    ->relationship('variants')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Size / Name')
                                            ->required()
                                            ->placeholder('e.g. 60ml, 100g, Small'),

                                        TextInput::make('sku')
                                            ->label('SKU (Optional)')
                                            ->maxLength(100)
                                            ->placeholder('e.g. PROD-500G')
                                            ->helperText('Leave blank if not needed'),

                                        TextInput::make('price')
                                            ->label('Price ($)')
                                            ->numeric()
                                            ->required()
                                            ->prefix('$'),

                                        TextInput::make('compare_price')
                                            ->label('Compare Price ($)')
                                            ->numeric()
                                            ->prefix('$'),

                                        TextInput::make('sale_price')
                                            ->label('Sale Price ($)')
                                            ->numeric()
                                            ->prefix('$'),

                                        TextInput::make('stock')
                                            ->label('Stock')
                                            ->numeric()
                                            ->default(0),

                                        TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0),

                                        Toggle::make('is_default')
                                            ->label('Default Variant'),

                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add Variant')
                                    ->reorderable()
                                    ->helperText('Add size-based pricing variants. The selected variant price overrides the product price in the cart.'),
                            ]),

                        Tabs\Tab::make('Additional Info')
                            ->schema([
                                Repeater::make('key_herbal_ingredients')
                                    ->label('Key Herbal Ingredients')
                                    ->simple(
                                        TextInput::make('ingredient')
                                            ->placeholder('e.g. Korean Red Ginseng')
                                            ->required()
                                    )
                                    ->addActionLabel('Add Ingredient')
                                    ->columnSpanFull(),

                                Repeater::make('key_benefits')
                                    ->label('Key Benefits')
                                    ->simple(
                                        TextInput::make('benefit')
                                            ->placeholder('e.g. Supports energy and stamina')
                                            ->required()
                                    )
                                    ->addActionLabel('Add Benefit')
                                    ->columnSpanFull(),

                                Repeater::make('specifications')
                                    ->label('Specifications')
                                    ->schema([
                                        TextInput::make('label')
                                            ->label('Property')
                                            ->required()
                                            ->placeholder('e.g. Form, Serving Size'),
                                        TextInput::make('value')
                                            ->label('Value')
                                            ->required()
                                            ->placeholder('e.g. Herbal Powder, 5 gm'),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Add Specification')
                                    ->columnSpanFull(),

                                Repeater::make('indications')
                                    ->label('Indications')
                                    ->simple(
                                        TextInput::make('indication')
                                            ->placeholder('e.g. Male vitality')
                                            ->required()
                                    )
                                    ->addActionLabel('Add Indication')
                                    ->columnSpanFull(),

                                Textarea::make('allergen_info')
                                    ->label('Allergen Information')
                                    ->placeholder('e.g. Manufactured in a facility that may process nuts, dairy...')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('other_ingredients')
                                    ->label('Other Ingredients')
                                    ->placeholder('e.g. Natural herbal extracts, botanical ingredients...')
                                    ->rows(3)
                                    ->columnSpanFull(),
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
