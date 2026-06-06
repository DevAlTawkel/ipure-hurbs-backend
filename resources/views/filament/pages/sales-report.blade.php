<x-filament-panels::page>

    {{-- Period Filter --}}
    <div class="flex gap-2 mb-6">
        @foreach(['7' => 'Last 7 Days', '30' => 'Last 30 Days', '90' => 'Last 90 Days', '0' => 'All Time'] as $value => $label)
            <button
                wire:click="$set('period', '{{ $value }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ $period === $value
                        ? 'bg-amber-500 text-white shadow'
                        : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Paid Orders</p>
            <p class="text-2xl font-bold text-amber-600">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Avg Order Value</p>
            <p class="text-2xl font-bold text-blue-600">${{ number_format($avgOrderValue, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Discount Given</p>
            <p class="text-2xl font-bold text-red-500">${{ number_format($totalDiscount, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Orders by Status --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4 uppercase tracking-wide">Orders by Status (All Time)</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b dark:border-gray-600">
                        <th class="pb-2">Status</th>
                        <th class="pb-2 text-right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordersByStatus as $status => $count)
                        <tr class="border-b dark:border-gray-700 last:border-0">
                            <td class="py-2 capitalize">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full
                                        {{ match($status) {
                                            'delivered' => 'bg-green-500',
                                            'shipped'   => 'bg-blue-500',
                                            'confirmed', 'processing' => 'bg-amber-500',
                                            'cancelled', 'refunded'  => 'bg-red-500',
                                            default     => 'bg-gray-400',
                                        } }}">
                                    </span>
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="py-2 text-right font-semibold">{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Top 10 Products --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4 uppercase tracking-wide">Top Products by Units Sold</h3>
            @if($topProducts->isEmpty())
                <p class="text-gray-400 text-sm">No sales data for this period.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 border-b dark:border-gray-600">
                            <th class="pb-2">Product</th>
                            <th class="pb-2 text-right">Units</th>
                            <th class="pb-2 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $p)
                            <tr class="border-b dark:border-gray-700 last:border-0">
                                <td class="py-2">
                                    <div class="font-medium">{{ $p->product_name }}</div>
                                    @if($p->product_sku)
                                        <div class="text-xs text-gray-400">{{ $p->product_sku }}</div>
                                    @endif
                                </td>
                                <td class="py-2 text-right font-semibold">{{ $p->total_qty }}</td>
                                <td class="py-2 text-right text-green-600">${{ number_format($p->total_revenue, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Revenue by Day Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700 mb-8">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4 uppercase tracking-wide">Revenue by Day</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b dark:border-gray-600">
                        <th class="pb-2">Date</th>
                        <th class="pb-2 text-right">Orders</th>
                        <th class="pb-2 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByDay as $row)
                        @if($row['orders'] > 0 || $row['revenue'] > 0)
                        <tr class="border-b dark:border-gray-700 last:border-0">
                            <td class="py-2 text-gray-600 dark:text-gray-300">{{ $row['date'] }}</td>
                            <td class="py-2 text-right">{{ $row['orders'] }}</td>
                            <td class="py-2 text-right font-semibold text-green-600">${{ number_format($row['revenue'], 2) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    @if($revenueByDay->where('orders', '>', 0)->isEmpty())
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-400">No revenue data for this period.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4 uppercase tracking-wide">Recent Paid Orders</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b dark:border-gray-600">
                        <th class="pb-2">Order #</th>
                        <th class="pb-2">Customer</th>
                        <th class="pb-2 text-right">Items</th>
                        <th class="pb-2 text-right">Total</th>
                        <th class="pb-2 text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b dark:border-gray-700 last:border-0">
                            <td class="py-2 font-mono text-xs text-amber-600">{{ $order->order_number }}</td>
                            <td class="py-2">
                                <div>{{ $order->shipping_name }}</div>
                                <div class="text-xs text-gray-400">{{ $order->customerEmail() }}</div>
                            </td>
                            <td class="py-2 text-right">{{ $order->items->count() }}</td>
                            <td class="py-2 text-right font-semibold text-green-600">${{ number_format($order->total, 2) }}</td>
                            <td class="py-2 text-right text-gray-400">{{ $order->paid_at?->format('d M, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-400">No paid orders in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-filament-panels::page>
