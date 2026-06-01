<?php

namespace App\Filament\Resources\Coupons;

use App\Models\Coupon;
use BackedEnum;
use Filament\Forms\Components\DateTimePickerComponent;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlineTicket;

    protected static ?string $navigationLabel = 'Coupons';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Coupon Information')
                ->schema([
                    TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->helperText('Unique coupon code'),

                    Textarea::make('description')
                        ->columnSpanFull(),

                    Select::make('discount_type')
                        ->options([
                            'percentage' => 'Percentage (%)',
                            'fixed' => 'Fixed Amount ($)',
                        ])
                        ->required(),

                    TextInput::make('discount_value')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->suffix(fn (callable $get) => $get('discount_type') === 'percentage' ? '%' : '$'),

                    TextInput::make('minimum_spend')
                        ->numeric()
                        ->prefix('$')
                        ->helperText('Minimum order amount to use this coupon'),
                ]),

            Section::make('Validity & Limits')
                ->schema([
                    DateTimePickerComponent::make('valid_from')
                        ->required(),

                    DateTimePickerComponent::make('valid_until')
                        ->helperText('Leave empty for no expiry'),

                    TextInput::make('usage_limit')
                        ->numeric()
                        ->helperText('Leave empty for unlimited usage'),

                    TextInput::make('usage_count')
                        ->numeric()
                        ->disabled()
                        ->label('Times Used'),

                    Toggle::make('is_active')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                BadgeColumn::make('discount_type')
                    ->colors([
                        'percentage' => 'info',
                        'fixed' => 'success',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('discount_value')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->discount_type === 'percentage' ? "{$state}%" : "\${$state}"
                    ),

                TextColumn::make('usage_count')
                    ->label('Used / Limit')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->usage_limit
                            ? "{$state} / {$record->usage_limit}"
                            : "{$state} / ∞"
                    ),

                BadgeColumn::make('is_active')
                    ->colors([
                        'true' => 'success',
                        'false' => 'danger',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive'),

                TextColumn::make('valid_until')
                    ->label('Expires')
                    ->dateTime()
                    ->default('Never'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active'),
                SelectFilter::make('discount_type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
