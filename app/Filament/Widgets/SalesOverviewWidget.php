<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders  = Order::count();
        $paidOrders   = Order::where('payment_status', 'paid')->count();
        $avgOrderValue = $paidOrders > 0
            ? Order::where('payment_status', 'paid')->avg('total')
            : 0;

        $pendingOrders    = Order::where('status', 'pending')->count();
        $totalCustomers   = Customer::count();

        // Last 7 days revenue trend
        $revenueTrend = collect(range(6, 0))->map(fn ($d) => (float) Order::where('payment_status', 'paid')
            ->whereDate('paid_at', now()->subDays($d)->toDateString())
            ->sum('total')
        )->values()->toArray();

        // Last 7 days order count trend
        $orderTrend = collect(range(6, 0))->map(fn ($d) => (int) Order::whereDate('created_at', now()->subDays($d)->toDateString())
            ->count()
        )->values()->toArray();

        return [
            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('All paid orders')
                ->descriptionColor('success')
                ->chart($revenueTrend)
                ->chartColor('success')
                ->color('success'),

            Stat::make('Total Orders', $totalOrders)
                ->description("{$pendingOrders} pending")
                ->descriptionColor('warning')
                ->chart($orderTrend)
                ->chartColor('warning')
                ->color('warning'),

            Stat::make('Paid Orders', $paidOrders)
                ->description('Successfully completed')
                ->descriptionColor('info')
                ->color('info'),

            Stat::make('Avg Order Value', '$' . number_format($avgOrderValue, 2))
                ->description('Per paid order')
                ->color('primary'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Registered accounts')
                ->color('gray'),
        ];
    }
}
