<div class="p-6 bg-gray-50/50 min-h-screen space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Financial Reports</h1>
            <p class="text-sm text-gray-500 font-medium">Analyze and track your expenses across various dimensions.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 shadow-sm hover:shadow-md transition-all flex items-center space-x-2">
                <i class="fas fa-print"></i>
                <span>Print Report</span>
            </button>
        </div>
    </div>

    <!-- Report Selection Tabs -->
    <div class="bg-white p-1 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap gap-1">
        @foreach([
            'category_summary' => ['icon' => 'fa-tags', 'label' => 'Category Summary'],
            'shop_expenditure' => ['icon' => 'fa-shop', 'label' => 'Shop Spending'],
            'user_statement' => ['icon' => 'fa-user-invoice', 'label' => 'User Statements'],
            'bank_audit' => ['icon' => 'fa-university', 'label' => 'Bank Audit'],
            'item_trend' => ['icon' => 'fa-chart-line', 'label' => 'Item Trends'],
        ] as $key => $info)
            <button wire:click="setReport('{{ $key }}')" 
                class="flex-1 min-w-[150px] px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center space-x-2 {{ $activeReport === $key ? 'bg-gray-900 text-white shadow-lg shadow-gray-200' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fas {{ $info['icon'] }}"></i>
                <span>{{ $info['label'] }}</span>
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-6">Filters</h3>
                
                <div class="space-y-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Date Range</label>
                        <div class="space-y-2">
                            <input type="date" wire:model.live="dateFrom" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                            <input type="date" wire:model.live="dateTo" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Quartz Filter -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Quartz</label>
                        <select wire:model.live="selectedQuartz" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Quartzs</option>
                            @foreach($quartzes as $q)
                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(in_array($activeReport, ['shop_expenditure', 'item_trend']))
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Shop</label>
                            <select wire:model.live="selectedShop" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Shops</option>
                                @foreach($shops as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeReport === 'user_statement')
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">User</label>
                            <select wire:model.live="selectedUser" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Users</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if($activeReport === 'bank_audit')
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Bank Account</label>
                            <select wire:model.live="selectedBank" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Accounts</option>
                                @foreach($banks as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->account_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Type</label>
                            <select wire:model.live="transactionType" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Types</option>
                                <option value="deposit">Deposits</option>
                                <option value="withdrawal">Withdrawals</option>
                            </select>
                        </div>
                    @endif

                    @if($activeReport === 'item_trend')
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Item</label>
                            <select wire:model.live="selectedItem" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Item</option>
                                @foreach($items as $i)
                                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <button wire:click="generateReport" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black shadow-lg shadow-indigo-100 transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-sync"></i>
                        <span>Refresh Data</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Data Display -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Visual Representation -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8" wire:ignore id="reportChartContainer">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Visual Analysis</h3>
                    <div id="reportChartType" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase">
                        {{ str_replace('_', ' ', $activeReport) }}
                    </div>
                </div>
                <div id="reportChart" class="w-full h-80"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
                <!-- Report Contents based on Selection -->
                @if($activeReport === 'category_summary')
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Spending by Category</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                @forelse($reportData as $cat => $total)
                                    <div class="flex items-center justify-between group">
                                        <div class="flex-1 pr-4">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs font-bold text-gray-700">{{ $cat }}</span>
                                                <span class="text-xs font-black text-gray-900">LKR {{ number_format($total, 2) }}</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-gray-100 rounded-full">
                                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ min(100, ($total / max(1, $reportData->sum())) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 italic text-sm">No data available for this range.</p>
                                @endforelse
                            </div>
                            <div class="bg-gray-50 rounded-3xl p-8 flex flex-col items-center justify-center border border-dashed border-gray-200">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Total Period Spending</p>
                                <p class="text-4xl font-black text-indigo-600">LKR {{ number_format($reportData->sum(), 2) }}</p>
                            </div>
                        </div>
                    </div>

                @elseif($activeReport === 'shop_expenditure')
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Expenditure by Shop</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase">Shop Name</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase text-right">Total Amount</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase text-right">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($reportData as $shop => $amount)
                                        <tr>
                                            <td class="py-4 text-xs font-bold text-gray-800">{{ $shop }}</td>
                                            <td class="py-4 text-xs font-black text-gray-900 text-right">LKR {{ number_format($amount, 2) }}</td>
                                            <td class="py-4 text-right">
                                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">
                                                    {{ number_format(($amount / max(1, $reportData->sum())) * 100, 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($reportData->isEmpty())
                                        <tr><td colspan="3" class="py-12 text-center text-gray-400 italic">No records found.</td></tr>
                                    @endif
                                </tbody>
                                <tfoot class="border-t-2 border-gray-100">
                                    <tr>
                                        <td class="py-4 text-xs font-black text-gray-400 uppercase">Total</td>
                                        <td class="py-4 text-sm font-black text-gray-900 text-right">LKR {{ number_format($reportData->sum(), 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                @elseif($activeReport === 'user_statement')
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">User Financial Statements</h3>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($reportData as $data)
                                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                    <div>
                                        <p class="text-sm font-black text-gray-800">{{ $data['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $data['email'] }}</p>
                                    </div>
                                    <div class="grid grid-cols-3 gap-8">
                                        <div class="text-center">
                                            <p class="text-[10px] font-black text-gray-400 uppercase">Pending Debt</p>
                                            <p class="text-sm font-black text-red-600">LKR {{ number_format($data['pending_debt'], 2) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-[10px] font-black text-gray-400 uppercase">Total Deposits</p>
                                            <p class="text-sm font-black text-blue-600">LKR {{ number_format($data['total_deposits'], 2) }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-[10px] font-black text-gray-400 uppercase">Available Credit</p>
                                            <p class="text-sm font-black text-emerald-600">LKR {{ number_format($data['available_credit'], 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif($activeReport === 'bank_audit')
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Bank Transaction Audit</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase">Date</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase">User</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase">Account</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase">Type</th>
                                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($reportData as $tx)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-4 text-[10px] font-bold text-gray-500">{{ $tx->transaction_date->format('M d, Y') }}</td>
                                            <td class="py-4 text-xs font-bold text-gray-800">{{ $tx->user->name }}</td>
                                            <td class="py-4 text-xs text-gray-500 font-medium">{{ $tx->bankAccount->name }}</td>
                                            <td class="py-4">
                                                <span class="text-[10px] font-black uppercase px-2 py-1 rounded-lg {{ $tx->type === 'deposit' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $tx->type }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-xs font-black text-right {{ $tx->type === 'deposit' ? 'text-emerald-600' : 'text-red-600' }}">
                                                {{ $tx->type === 'deposit' ? '+' : '-' }} LKR {{ number_format($tx->amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                @elseif($activeReport === 'item_trend')
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Item Price History & Trend</h3>
                        @if($reportData->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($reportData as $row)
                                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between group">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-center bg-white p-2 rounded-xl shadow-sm min-w-[80px]">
                                                <p class="text-[10px] font-black text-indigo-600 uppercase">{{ date('M', strtotime($row['date'])) }}</p>
                                                <p class="text-lg font-black text-gray-800">{{ date('d', strtotime($row['date'])) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-800">{{ $row['shop'] }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $row['quantity'] }} {{ $row['unit'] }} purchased</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-gray-900">LKR {{ number_format($row['unit_price'], 2) }} <span class="text-[10px] text-gray-400 font-medium">/ unit</span></p>
                                            <p class="text-[10px] font-bold text-gray-400">Total: LKR {{ number_format($row['total_price'], 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-20">
                                <i class="fas fa-search text-4xl text-gray-200 mb-4"></i>
                                <p class="text-gray-400 font-medium">Please select an item to view its trend.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart = null;

            const initChart = () => {
                const chartType = '{{ $activeReport }}';
                const container = document.querySelector("#reportChart");
                if (!container) return;

                if (chart) chart.destroy();

                let options = {
                    chart: {
                        height: 320,
                        fontFamily: 'Inter, sans-serif',
                        animations: { enabled: true, easing: 'easeinout', speed: 800 }
                    },
                    colors: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                    tooltip: { theme: 'dark' }
                };

                const data = @json($chartData);

                if (chartType === 'category_summary' || chartType === 'shop_expenditure') {
                    options = {
                        ...options,
                        series: data.series || [],
                        labels: data.labels || [],
                        chart: { ...options.chart, type: 'donut' },
                        legend: { position: 'bottom' },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            formatter: (w) => "LKR " + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString()
                                        }
                                    }
                                }
                            }
                        }
                    };
                } else if (chartType === 'user_statement') {
                    options = {
                        ...options,
                        series: [
                            { name: 'Pending Debt', data: data.debt || [] },
                            { name: 'Available Credit', data: data.credit || [] }
                        ],
                        chart: { ...options.chart, type: 'bar', stacked: false },
                        xaxis: { categories: data.labels || [] },
                        colors: ['#EF4444', '#10B981'],
                        plotOptions: {
                            bar: {
                                borderRadius: 8,
                                columnWidth: '50%'
                            }
                        }
                    };
                } else if (chartType === 'item_trend') {
                    options = {
                        ...options,
                        series: [{ name: 'Unit Price', data: data.series || [] }],
                        chart: { ...options.chart, type: 'area' },
                        xaxis: { categories: data.labels || [] },
                        stroke: { curve: 'smooth' },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [20, 100, 100, 100]
                            }
                        }
                    };
                }

                if (options.series && (Array.isArray(options.series) ? options.series.length > 0 : true)) {
                    chart = new ApexCharts(container, options);
                    chart.render();
                }
            };

            initChart();

            Livewire.on('reportGenerated', () => {
                setTimeout(initChart, 100);
            });
            
            // Re-init on navigate
            document.addEventListener('livewire:navigated', initChart);
        });
    </script>
</div>
