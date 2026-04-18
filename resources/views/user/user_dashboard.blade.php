@extends('layouts.customer')

@section('title', 'My Dashboard | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-3 py-12 max-w-7xl">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-2xl md:text-4xl font-playfair font-medium text-gray-900 mb-2">Dashboard</h1>
                <p class="text-gray-600">Welcome back, {{ Auth::user()->first_name }}!</p>
            </div>
            <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 text-amber-500 hover:text-amber-600   rounded-full transition-colors text-sm font-semibold flex items-center ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-chevron-right-icon lucide-circle-chevron-right size-10">
                    <circle cx="12" cy="12" r="10"/><path d="m10 8 4 4-4 4"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="lg:col-span-2 space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
                    <div class="bg-white rounded-2xl border border-gray-200 border-t-4 border-t-amber-300 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold tracking-wide uppercase text-black">Total Purchases</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg  text-amber-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"></path></svg>
                            </span>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-black">{{ $totalPurchases }}</p>
                        <div id="purchasesSparkline" class="mt-2 h-10"></div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 border-t-4 border-t-amber-300 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold tracking-wide uppercase text-black">Pending Orders</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg  text-amber-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"></path></svg>
                            </span>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-black">{{ $pendingPurchases }}</p>
                        <div id="pendingSparkline" class="mt-2 h-10"></div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 border-t-4 border-t-amber-300 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold tracking-wide uppercase text-black">Delivered</p>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg  text-amber-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                            </span>
                        </div>
                        <p class="mt-4 text-3xl font-bold text-black">{{ $completedPurchases }}</p>
                        <div id="deliveredSparkline" class="mt-2 h-10"></div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 border-t-4 border-t-amber-300 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold tracking-wide uppercase text-black">Total Spent</p>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 text-amber-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                            </svg>
                        </div>
                        <p class="mt-2 text-2xl font-bold text-black">PHP {{ number_format($totalSpent, 2) }}</p>
                        <div id="spentSparkline" class="mt-2 h-10"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg  text-amber-300">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 15.75 8.25 11.25l3 3 5.25-6"></path></svg>
                            </span>
                            Total Spent
                        </h3>
                        <div class="h-64">
                            <div id="spendingTrendChart" class="h-full"></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg  text-amber-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3v18h18M7.5 13.5V9.75m4.5 3.75V6.75m4.5 6.75V11.25"></path></svg>
                            </span>
                            Order Status Breakdown
                        </h3>
                        <div class="h-64">
                            <div id="statusBreakdownChart" class="h-full"></div>
                        </div>
                    </div>
                </div>

                <div id="recentOrdersSection" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 ">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Recent Orders
                </h3>               

    @if($orders->isEmpty())
        <div class="text-center py-10">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <p class="text-gray-900 font-bold text-lg mb-1">No orders found</p>
            <p class="text-gray-600 mb-6">You haven't purchased any luxury items yet.</p>
            <a href="{{ route('products.index') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">
                Browse Collection
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Order</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Items</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Payment</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap">#{{ $order->display_order_number }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $order->items->count() }}</td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $order->payment_display }}</td>
                            <td class="px-4 py-3 text-right text-gray-900 whitespace-nowrap">PHP {{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide
                                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status === 'confirmed' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-sky-100 text-sky-700' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($orders->hasPages())
        <div class="mt-5 flex items-center justify-end gap-2">
            @if($orders->onFirstPage())
                <span class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">Prev</span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="js-recent-orders-link px-3 py-1.5 text-xs rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">Prev</a>
            @endif

            @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 1), min($orders->lastPage(), $orders->currentPage() + 1)) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="px-3 py-1.5 text-xs rounded-lg bg-amber-300 text-black font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="js-recent-orders-link px-3 py-1.5 text-xs rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="js-recent-orders-link px-3 py-1.5 text-xs rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">Next</a>
            @else
                <span class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
            @endif
        </div>
    @endif
</div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', async function (event) {
                const link = event.target.closest('.js-recent-orders-link');
                if (!link) return;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

                const section = document.getElementById('recentOrdersSection');
                if (!section) return;

                event.preventDefault();
                section.classList.add('opacity-60', 'pointer-events-none');
                section.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(link.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        window.location.href = link.href;
                        return;
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const incoming = doc.getElementById('recentOrdersSection');

                    if (!incoming) {
                        window.location.href = link.href;
                        return;
                    }

                    section.outerHTML = incoming.outerHTML;
                    window.history.replaceState({}, '', link.href);
                } catch (error) {
                    window.location.href = link.href;
                } finally {
                    const updated = document.getElementById('recentOrdersSection');
                    if (updated) {
                        updated.classList.remove('opacity-60', 'pointer-events-none');
                        updated.removeAttribute('aria-busy');
                    }
                }
            });

            if (typeof ApexCharts === 'undefined') return;

            const spendingSeries = @json($spendingChartData).map((value) => Number(value) || 0);
            const statusSeries = @json($statusChartData).map((value) => Number(value) || 0);
            const safeSpentSeries = spendingSeries.some((value) => value > 0) ? spendingSeries : [20, 28, 22, 30, 25, 32];

            const sparklineConfigs = [
                {
                    selector: '#purchasesSparkline',
                    color: '#f59e0b',
                    data: safeSpentSeries.map((value, index) => Math.max(1, Math.round((value / 300) + (index + 1))))
                },
                {
                    selector: '#pendingSparkline',
                    color: '#f59e0b',
                    data: [
                        statusSeries[0] ?? 0,
                        statusSeries[1] ?? 0,
                        statusSeries[2] ?? 0,
                        statusSeries[2] ?? 0,
                        statusSeries[1] ?? 0,
                        statusSeries[0] ?? 0,
                    ]
                },
                {
                    selector: '#deliveredSparkline',
                    color: '#f59e0b',
                    data: [
                        0,
                        statusSeries[3] ?? 0,
                        statusSeries[3] ?? 0,
                        statusSeries[4] ?? 0,
                        statusSeries[4] ?? 0,
                        statusSeries[4] ?? 0,
                    ]
                },
                {
                    selector: '#spentSparkline',
                    color: '#f59e0b',
                    data: safeSpentSeries
                },
            ];

            sparklineConfigs.forEach(function (config) {
                const el = document.querySelector(config.selector);
                if (!el) return;

                const hasValue = config.data.some((value) => Number(value) > 0);
                const normalizedData = hasValue ? config.data : [1, 1, 1, 1, 1, 1];
                const chart = new ApexCharts(el, {
                    chart: {
                        type: 'line',
                        height: 40,
                        sparkline: { enabled: true },
                        toolbar: { show: false },
                    },
                    series: [{ data: normalizedData }],
                    stroke: { curve: 'smooth', width: 2.3 },
                    colors: [config.color],
                    tooltip: { enabled: false },
                });
                chart.render();
            });

            const spendingEl = document.getElementById('spendingTrendChart');
            if (spendingEl) {
                const spendingChart = new ApexCharts(spendingEl, {
                    chart: {
                        type: 'area',
                        height: '100%',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    stroke: { curve: 'smooth', width: 3 },
                    series: [{
                        name: 'Spent (PHP)',
                        data: @json($spendingChartData)
                    }],
                    xaxis: {
                        categories: @json($spendingChartLabels),
                        labels: { style: { colors: '#6b7280', fontSize: '11px' } }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return 'PHP ' + Number(val).toLocaleString();
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    colors: ['#f59e0b'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05 }
                    },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return 'PHP ' + Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }
                    }
                });
                spendingChart.render();
            }

            const statusEl = document.getElementById('statusBreakdownChart');
            if (statusEl) {
                const statusChart = new ApexCharts(statusEl, {
                    chart: {
                        type: 'bar',
                        height: '100%',
                        toolbar: { show: false }
                    },
                    series: [{
                        name: 'Orders',
                        data: @json($statusChartData)
                    }],
                    xaxis: {
                        categories: @json($statusLabels),
                        labels: { style: { colors: '#6b7280', fontSize: '11px' } }
                    },
                    plotOptions: {
                        bar: { borderRadius: 6, columnWidth: '45%' }
                    },
                    yaxis: {
                        min: 0,
                        labels: {
                            formatter: function (val) {
                                return Number(val).toFixed(0);
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                    colors: ['#f59e0b'],
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return Number(val).toFixed(0) + ' order(s)';
                            }
                        }
                    }
                });
                statusChart.render();
            }
        });
    </script>
@endpush
