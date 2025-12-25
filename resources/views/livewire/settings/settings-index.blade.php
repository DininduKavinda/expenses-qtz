<div class="p-6 bg-gray-50/50 min-h-screen space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">System Settings & Status</h1>
            <p class="text-sm text-gray-500 font-medium">Monitor system activity, track commands, and review application health.</p>
        </div>
    </div>

    <!-- Tabbed Navigation -->
    <div class="bg-white p-1 rounded-2xl shadow-sm border border-gray-100 flex gap-1">
        @foreach([
            'activity' => ['icon' => 'fa-fingerprint', 'label' => 'System Activity'],
            'commands' => ['icon' => 'fa-terminal', 'label' => 'Artisan Commands'],
            'errors' => ['icon' => 'fa-bug', 'label' => 'App Errors'],
        ] as $tab => $info)
            <button wire:click="setTab('{{ $tab }}')" 
                class="flex-1 px-4 py-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center space-x-2 {{ $activeTab === $tab ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fas {{ $info['icon'] }}"></i>
                <span>{{ $info['label'] }}</span>
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 sticky top-6">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Filters</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search logs..." class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                    </div>

                    @if($activeTab !== 'errors')
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Date Range</label>
                            <div class="space-y-2">
                                <input type="date" wire:model.live="dateFrom" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold">
                                <input type="date" wire:model.live="dateTo" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">User</label>
                            <select wire:model.live="selectedUser" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Module</label>
                            <select wire:model.live="selectedModule" class="w-full bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Modules</option>
                                <option value="grn_sessions">GRN Sessions</option>
                                <option value="gdns">GDNs</option>
                                <option value="bank_accounts">Bank Accounts</option>
                                <option value="bank_transactions">Bank Transactions</option>
                                <option value="users">Users</option>
                                <option value="items">Items</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Display -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[500px]">
                @if($activeTab === 'errors')
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase w-48">Timestamp</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase w-24">Level</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase">Message</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($appErrors as $error)
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="p-4 text-[10px] font-mono text-gray-500">{{ $error['timestamp'] }}</td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase {{ $error['level'] === 'ERROR' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                                {{ $error['level'] }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-xs font-medium text-gray-700 max-w-xl">
                                            <div class="truncate hover:whitespace-normal transition-all" title="{{ $error['message'] }}">
                                                {{ $error['message'] }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="p-20 text-center text-gray-400 italic">No application errors found in log file.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase w-40">Date</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase w-32">User</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase w-32">Action</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-4 text-[10px] text-gray-500 font-bold">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="h-6 w-6 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-black text-indigo-600">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                                <span class="text-xs font-bold text-gray-700">{{ $log->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase 
                                                {{ in_array($log->action, ['created', 'login']) ? 'bg-emerald-100 text-emerald-700' : '' }}
                                                {{ in_array($log->action, ['deleted', 'logout']) ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $log->action === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ !$log->action ? 'bg-gray-100 text-gray-700' : '' }}">
                                                {{ $log->action ?: 'executed' }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-xs">
                                            @if($log->type === 'command')
                                                <code class="px-2 py-1 bg-gray-900 text-gray-100 rounded text-[10px] font-mono">php artisan {{ $log->action }}</code>
                                            @else
                                                <p class="text-gray-700 font-medium">
                                                    @if($log->table_name)
                                                        <span class="text-gray-400 uppercase text-[9px] font-black tracking-tighter">{{ $log->table_name }}</span>
                                                    @endif
                                                    @if($log->metadata)
                                                        @foreach($log->metadata as $key => $val)
                                                            <span class="inline-block bg-gray-100 px-1.5 py-0.5 rounded text-[10px] mr-1">{{ $key }}: {{ $val }}</span>
                                                        @endforeach
                                                    @endif
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-20 text-center text-gray-400 italic">No activity logs found for current filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            @if($activeTab === 'errors' && $appErrors->hasPages())
                <div class="mt-8">
                    {{ $appErrors->links() }}
                </div>
            @elseif($activeTab !== 'errors' && $logs->hasPages())
                <div class="mt-8">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
