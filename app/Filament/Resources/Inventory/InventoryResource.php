<?php

namespace App\Filament\Resources\Inventory;

use App\Models\Product;
use App\Models\StockMovement;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class InventoryResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineArchiveBox;

    protected static ?string $navigationLabel = 'Inventory Management';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Stock Movement')
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('movement_type')
                        ->options([
                            'purchase' => 'Purchase',
                            'return' => 'Return',
                            'adjustment' => 'Adjustment',
                            'damaged' => 'Damaged',
                            'lost' => 'Lost',
                        ])
                        ->required(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->required()
                        ->helperText('Positive for inflow, negative for outflow'),

                    TextInput::make('reference')
                        ->maxLength(255)
                        ->helperText('Order ID, Return ID, etc.'),

                    Textarea::make('notes')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('movement_type')
                    ->badge()
                    ->colors([
                        'purchase' => 'success',
                        'return' => 'info',
                        'adjustment' => 'warning',
                        'damaged' => 'danger',
                        'lost' => 'danger',
                    ]),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+{$state}" : "{$state}"),

                TextColumn::make('reference')
                    ->limit(50),

                TextColumn::make('createdBy.name')
                    ->label('Recorded By')
                    ->default('System'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('movement_type')
                    ->options([
                        'purchase' => 'Purchase',
                        'return' => 'Return',
                        'adjustment' => 'Adjustment',
                        'damaged' => 'Damaged',
                        'lost' => 'Lost',
                    ]),

                Filter::make('recent')
                    ->query(fn ($query) => $query->whereDate('created_at', '>=', now()->subDays(30)))
                    ->label('Last 30 Days'),
            ])
            ->recordActions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventory::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
        ];
    }
}
