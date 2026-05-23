<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReport extends Page
{
    protected string $view = 'filament.pages.sales-report';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Sales Report';

    protected static ?string $title = 'Sales Report';

    protected static ?int $navigationSort = 2;

    public string $period = '30';

    protected function getViewData(): array
    {
        $days = (int) $this->period;
        $from = $days === 0 ? null : now()->subDays($days)->startOfDay();

        $baseQuery = Order::where('payment_status', 'paid')
            ->when($from, fn ($q) => $q->where('paid_at', '>=', $from));

        $totalRevenue   = (float) (clone $baseQuery)->sum('total');
        $totalOrders    = (int)   (clone $baseQuery)->count();
        $avgOrderValue  = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalDiscount  = (float) (clone $baseQuery)->sum('discount_amount');

        // Orders by status (all time)
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Revenue by day (last N days)
        $revenueByDay = collect(range($days > 0 ? $days - 1 : 29, 0))
            ->map(fn ($d) => [
                'date'    => now()->subDays($d)->format('d M'),
                'revenue' => (float) Order::where('payment_status', 'paid')
                    ->whereDate('paid_at', now()->subDays($d)->toDateString())
                    ->sum('total'),
                'orders'  => (int) Order::where('payment_status', 'paid')
                    ->whereDate('paid_at', now()->subDays($d)->toDateString())
                    ->count(),
            ])
            ->values();

        // Top 10 products by qty sold
        $topProducts = OrderItem::select(
            'product_name',
            'product_sku',
            DB::raw('SUM(qty) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->when($from, fn ($q) => $q->whereHas(
                'order',
                fn ($o) => $o->where('payment_status', 'paid')->where('paid_at', '>=', $from)
            ), fn ($q) => $q->whereHas(
                'order',
                fn ($o) => $o->where('payment_status', 'paid')
            ))
            ->groupBy('product_name', 'product_sku')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Recent 10 paid orders
        $recentOrders = Order::with('items')
            ->where('payment_status', 'paid')
            ->when($from, fn ($q) => $q->where('paid_at', '>=', $from))
            ->latest('paid_at')
            ->limit(10)
            ->get();

        return compact(
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'totalDiscount',
            'ordersByStatus', 'revenueByDay', 'topProducts', 'recentOrders'
        );
    }
}
