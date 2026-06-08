<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->options([
                    'pending'    => 'Pending',
                    'confirmed'  => 'Confirmed',
                    'processing' => 'Processing',
                    'shipped'    => 'Shipped',
                    'delivered'  => 'Delivered',
                    'cancelled'  => 'Cancelled',
                    'refunded'   => 'Refunded',
                ])
                ->required(),

            TextInput::make('notes')->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->searchable()->copyable(),
                TextColumn::make('shipping_name')->label('Customer')->searchable(),
                TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float) $state, 2))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'confirmed'  => 'info',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        'refunded'   => 'danger',
                        default      => 'gray',
                    }),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'     => 'success',
                        'pending'  => 'warning',
                        'failed'   => 'danger',
                        'refunded' => 'danger',
                        default    => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'confirmed'  => 'Confirmed',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                        'refunded'   => 'Refunded',
                    ]),
                SelectFilter::make('payment_status')
                    ->options([
                        'pending'  => 'Pending',
                        'paid'     => 'Paid',
                        'failed'   => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Order Details')
                ->schema([
                    TextEntry::make('order_number'),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('payment_status')->badge(),
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('paid_at')->dateTime(),
                ])->columns(2),

            Section::make('Shipping Address')
                ->schema([
                    TextEntry::make('shipping_name')->label('Name'),
                    TextEntry::make('shipping_phone')->label('Phone'),
                    TextEntry::make('shipping_line1')->label('Address Line 1'),
                    TextEntry::make('shipping_line2')->label('Address Line 2'),
                    TextEntry::make('shipping_city')->label('City'),
                    TextEntry::make('shipping_state')->label('State'),
                    TextEntry::make('shipping_pincode')->label('Pincode'),
                ])->columns(2),

            Section::make('Pricing')
                ->schema([
                    TextEntry::make('subtotal')->money('USD'),
                    TextEntry::make('discount_amount')->money('USD'),
                    TextEntry::make('discount_reason'),
                    TextEntry::make('shipping_charge')->money('USD'),
                    TextEntry::make('total')->money('USD'),
                ])->columns(3),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view'  => ViewOrder::route('/{record}'),
        ];
    }
}
