<div class="p-4 md:p-8 space-y-8 bg-gray-50/50 min-h-screen">
    <!-- Header with Breadcrumbs & Date -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-indigo-600 mb-1 uppercase tracking-widest">
                <i class="fas fa-home"></i>
                <span>Overview</span>
                <i class="fas fa-chevron-right text-[8px] text-gray-300"></i>
                <span class="text-gray-400">Dashboard</span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Financial Center</h1>
        </div> --}}
        <!-- New Statistics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div
                    class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Monthly Spend
                </p>
                <p class="mt-2 text-xl font-black text-gray-900 leading-none">LKR
                    {{ number_format($monthlySpend, 0) }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div
                    class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-piggy-bank"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Total
                    Deposited</p>
                <p class="mt-2 text-xl font-black text-gray-900 leading-none">LKR
                    {{ number_format($lifetimeDeposits, 0) }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div
                    class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-box-open"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Unique Items
                </p>
                <p class="mt-2 text-xl font-black text-gray-900 leading-none">{{ $uniqueItemsCount }}</p>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 group hover:shadow-md transition-all">
                <div
                    class="h-10 w-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mb-4 group-hover:scale-110 transition-transform">
                    <i class="fas fa-store"></i>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Last Visited
                </p>
                <p class="mt-2 text-sm font-black text-gray-900 leading-tight truncate">{{ $lastShop }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 bg-white p-2 rounded-2xl shadow-sm border border-gray-100">
            <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase leading-none">Today is</p>
                <p class="text-sm font-bold text-gray-800">{{ now()->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Tiered Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Personal Section (Visible to Everyone) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Financial Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Spending Trend -->

                <div
                    class="relative overflow-hidden bg-gradient-to-br from-rose-500 to-red-600 rounded-3xl p-8 text-white shadow-xl shadow-rose-200 group">
                    <div
                        class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-white/80 text-sm font-medium uppercase tracking-wider">Your Pending Debt</h3>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-4xl font-black">{{ number_format($debt, 2) }}</span>
                            <span class="text-white/60 font-bold uppercase text-sm">LKR</span>
                        </div>
                        <p class="mt-4 text-sm text-white/70 italic">Please settle your dues via bank deposit.</p>
                        <button
                            class="mt-6 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold transition-colors">Vew
                            Details</button>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-8 text-white shadow-xl shadow-emerald-200 group">
                    <div
                        class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full group-hover:scale-110 transition-transform">
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-white/80 text-sm font-medium uppercase tracking-wider">Available Credit</h3>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-4xl font-black">{{ number_format($credit, 2) }}</span>
                            <span class="text-white/60 font-bold uppercase text-sm">LKR</span>
                        </div>
                        <p class="mt-4 text-sm text-white/70 italic">Deposits waiting for expense matching.</p>
                        <button
                            class="mt-6 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold transition-colors">Add
                            Money</button>
                    </div>
                </div>

                {{-- <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 md:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Personal Spending (Last 7
                            Days)</h3>
                        <div
                            class="flex items-center space-x-1 text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-lg">
                            <i class="fas fa-chart-line"></i>
                            <span>Trend</span>
                        </div>
                    </div>
                    <div id="personalTrendChart" class="w-full h-48" wire:ignore></div>
                </div> --}}


            </div>



            <!-- Recent Personal Expenses -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Recent Personal Expenses</h3>
                    <a wire:navigate href="{{ route('my-expenses') }}"
                        class="text-xs font-bold text-indigo-600 hover:underline">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentPersonalExpenses as $expense)
                        <div
                            class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                    <i class="fas fa-receipt text-indigo-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $expense->grnItem->item->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $expense->grnItem->grnSession->shop->name }} •
                                        {{ $expense->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900">LKR {{ number_format($expense->amount, 2) }}</p>
                                <span
                                    class="text-[10px] font-bold text-orange-500 bg-orange-100 px-2 py-0.5 rounded-full uppercase">{{ $expense->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-400 italic">No recent expenses found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Section (Quartz & Global Activity) -->
        <div class="space-y-8">
            @if($quartzStats)
                <!-- Quartz House Summary -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 -z-0"></div>
                    <div class="relative z-10">
                        <div class="flex items-center space-x-2 text-indigo-600 mb-4">
                            <i class="fas fa-home"></i>
                            <span class="text-xs font-black uppercase tracking-widest">{{ $quartzStats['name'] }}</span>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-1">House Overview</h3>
                        <p class="text-xs text-gray-500 mb-6">Combined statistics for your quartz.</p>

                        <!-- House Spending Distribution -->
                        <div class="mb-6" wire:ignore>
                            <div id="quartzShopChart" class="w-full h-40"></div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50">
                                <span class="text-xs font-bold text-gray-500">Total House Debt</span>
                                <span class="text-sm font-black text-red-600">LKR
                                    {{ number_format($quartzStats['total_debt'], 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50">
                                <span class="text-xs font-bold text-gray-500">Bank Balance</span>
                                <span class="text-sm font-black text-emerald-600">LKR
                                    {{ number_format($quartzStats['bank_balance'], 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Recent Sessions</h4>
                            <div class="space-y-3">
                                @foreach($quartzStats['recent_sessions'] as $session)
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                            <i class="fas fa-shopping-cart text-indigo-400 text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-gray-800 truncate">{{ $session->shop->name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $session->session_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold {{ $session->status === 'confirmed' ? 'text-green-500' : 'text-orange-500' }}">{{ ucfirst($session->status) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($isAdmin && $adminStats)
                <!-- Global Activity -->
                <div class="bg-gray-900 rounded-3xl p-6 text-white shadow-2xl">
                    <h3 class="text-lg font-bold mb-4">System Activity</h3>
                    <div class="space-y-4">
                        @foreach($adminStats['recent_global_activity'] as $activity)
                            <div class="flex items-center space-x-3 border-l-2 border-indigo-500 pl-4 py-1">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-200">{{ $activity->quartz->name }} @
                                        {{ $activity->shop->name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-[10px] font-bold text-indigo-400">{{ ucfirst($activity->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <button
                        class="w-full mt-6 py-3 bg-white/10 hover:bg-white/20 rounded-2xl text-xs font-bold transition-all">Go
                        to Admin Hub</button>
                </div>
            @endif
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const initCharts = () => {
                const personalCtx = document.querySelector("#personalTrendChart");
                if (personalCtx) {
                    new ApexCharts(personalCtx, {
                        series: [{
                            name: "Amount Spent",
                            data: {!! json_encode($personalChartData['data']) !!}
                        }],
                        chart: {
                            type: 'area',
                            height: 180,
                            sparkline: { enabled: true }
                        },
                        stroke: { curve: 'smooth', width: 3 },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [20, 100, 100, 100]
                            }
                        },
                        colors: ['#4F46E5'],
                        labels: {!! json_encode($personalChartData['labels']) !!},
                        tooltip: {
                            theme: 'dark',
                            y: { formatter: (val) => "LKR " + val.toLocaleString() }
                        }
                    }).render();
                }

                @if($quartzStats)
                    const quartzCtx = document.querySelector("#quartzShopChart");
                    if (quartzCtx) {
                        new ApexCharts(quartzCtx, {
                            series: {!! json_encode($quartzStats['chart_data']) !!},
                            chart: {
                                type: 'donut',
                                height: 160,
                                sparkline: { enabled: true }
                            },
                            labels: {!! json_encode($quartzStats['chart_labels']) !!},
                            colors: ['#6366F1', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981'],
                            dataLabels: { enabled: false },
                            legend: { show: false },
                            tooltip: { theme: 'dark' }
                        }).render();
                    }
                @endif
            };

            initCharts();
            document.addEventListener('livewire:navigated', initCharts);
        });
    </script>
</div>