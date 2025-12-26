<div class="p-4 bg-gray-50/50 min-h-screen space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">System Settings & Status</h1>
            <p class="text-sm text-gray-500">Monitor system activity, track commands, and review application health.</p>
        </div>
        
        <!-- Quick Actions - Mobile Only -->
        <div class="md:hidden grid grid-cols-2 gap-2">
            <button onclick="toggleCommandModal()" 
                class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-center gap-1">
                <i class="fas fa-terminal"></i>
                <span>Run Command</span>
            </button>
            <button onclick="clearCache()" 
                class="bg-orange-500 text-white px-3 py-2 rounded-lg text-xs font-medium flex items-center justify-center gap-1">
                <i class="fas fa-broom"></i>
                <span>Clear Cache</span>
            </button>
        </div>
    </div>

    <!-- Tabbed Navigation - Mobile Scrollable -->
    <div class="bg-white p-1 rounded-xl shadow-sm border border-gray-100 flex gap-1 overflow-x-auto no-scrollbar">
        @foreach([
                'activity' => ['icon' => 'fa-fingerprint', 'label' => 'Activity'],
                'commands' => ['icon' => 'fa-terminal', 'label' => 'Commands'],
                'errors' => ['icon' => 'fa-bug', 'label' => 'Errors'],
                'maintenance' => ['icon' => 'fa-tools', 'label' => 'Maintenance'],
            ] as $tab => $info)
                <button wire:click="setTab('{{ $tab }}')" 
                    class="flex-shrink-0 px-3 py-2 md:px-4 md:py-3 rounded-lg text-xs font-medium transition-all flex items-center justify-center gap-2 whitespace-nowrap 
                        {{ $activeTab === $tab ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fas {{ $info['icon'] }} text-xs"></i>
                    <span>{{ $info['label'] }}</span>
                </button>
        @endforeach
    </div>

    @if($activeTab === 'maintenance')
        <!-- Maintenance Commands Tab -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-tools text-orange-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">System Maintenance</h3>
            </div>

            <div class="space-y-4">
                <!-- Danger Zone -->
                <div class="border border-red-200 rounded-xl bg-red-50 p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                        <h4 class="font-semibold text-red-700">Danger Zone</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <button wire:click="runCommand('migrate:fresh --seed')" 
                                wire:confirm="⚠️ WARNING: This will wipe ALL database data and reseed from scratch. Are you sure?"
                                class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-database"></i>
                            <span>Fresh Migrate & Seed</span>
                        </button>
                        <button wire:click="runCommand('db:wipe')" 
                                wire:confirm="⚠️ WARNING: This will delete ALL database tables. Are you sure?"
                                class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            <span>Wipe Database</span>
                        </button>
                    </div>
                </div>

                <!-- Optimization Commands -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Optimization</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <button wire:click="runCommand('optimize:clear')" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-broom"></i>
                            <span>Clear Cache</span>
                        </button>
                        <button wire:click="runCommand('config:clear')" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-cog"></i>
                            <span>Clear Config</span>
                        </button>
                        <button wire:click="runCommand('route:clear')" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-route"></i>
                            <span>Clear Routes</span>
                        </button>
                        <button wire:click="runCommand('view:clear')" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i>
                            <span>Clear Views</span>
                        </button>
                        <button wire:click="runCommand('cache:clear')" 
                                class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            <span>Clear All Cache</span>
                        </button>
                        <button wire:click="runCommand('optimize')" 
                                class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-bolt"></i>
                            <span>Optimize</span>
                        </button>
                    </div>
                </div>

                <!-- Database Commands -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Database</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <button wire:click="runCommand('migrate')" 
                                class="bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-database"></i>
                            <span>Run Migrations</span>
                        </button>
                        <button wire:click="runCommand('migrate:rollback')" 
                                class="bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i>
                            <span>Rollback Migrations</span>
                        </button>
                        <button wire:click="runCommand('db:seed')" 
                                class="bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-seedling"></i>
                            <span>Seed Database</span>
                        </button>
                    </div>
                </div>

                <!-- Custom Command Input -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Custom Command</h4>
                    <div class="flex flex-col md:flex-row gap-3">
                        <input type="text" wire:model="customCommand" 
                               placeholder="Enter artisan command (without 'php artisan')" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <button wire:click="runCustomCommand" 
                                class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            Run Command
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Example: make:controller, make:model, storage:link, etc.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar - Mobile Collapsible -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 lg:sticky lg:top-6">
                    <!-- Mobile Filter Toggle -->
                    <button onclick="toggleFilters()"
                        class="lg:hidden w-full flex items-center justify-between p-3 mb-4 bg-gray-50 rounded-lg border border-gray-200">
                        <span class="font-medium text-gray-700 flex items-center gap-2">
                            <i class="fas fa-filter"></i>
                            Filters
                        </span>
                        <i id="filterIcon" class="fas fa-chevron-down transition-transform"></i>
                    </button>

                    <div id="filterSection" class="hidden lg:block">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase mb-4">Filters</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-2">Search</label>
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="searchQuery" 
                                           placeholder="Search logs..." 
                                           class="w-full bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            @if($activeTab !== 'errors')
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-2">Date Range</label>
                                    <div class="space-y-2">
                                        <input type="date" wire:model.live="dateFrom" 
                                               class="w-full bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                                        <input type="date" wire:model.live="dateTo" 
                                               class="w-full bg-gray-50 px-4 py-2 rounded-lg border border-gray-200">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-2">User</label>
                                    <div class="relative">
                                        <select wire:model.live="selectedUser" 
                                                class="w-full bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 appearance-none">
                                            <option value="">All Users</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-2">Module</label>
                                    <div class="relative">
                                        <select wire:model.live="selectedModule" 
                                                class="w-full bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 appearance-none">
                                            <option value="">All Modules</option>
                                            <option value="grn_sessions">GRN Sessions</option>
                                            <option value="gdns">GDNs</option>
                                            <option value="bank_accounts">Bank Accounts</option>
                                            <option value="bank_transactions">Bank Transactions</option>
                                            <option value="users">Users</option>
                                            <option value="items">Items</option>
                                        </select>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                            <i class="fas fa-cubes text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Display -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($activeTab === 'errors')
                        <!-- Errors Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Timestamp</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Level</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Message</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($appErrors as $error)
                                        <tr class="hover:bg-red-50/30 transition-colors">
                                            <td class="p-4 text-xs text-gray-600">{{ $error['timestamp'] }}</td>
                                            <td class="p-4">
                                                <span class="px-2 py-1 rounded text-xs font-medium 
                                                    {{ $error['level'] === 'ERROR' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                                    {{ $error['level'] }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-sm text-gray-700">
                                                <div class="truncate hover:whitespace-normal transition-all" 
                                                     title="{{ $error['message'] }}">
                                                    {{ $error['message'] }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="p-12 text-center text-gray-400">No application errors found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @elseif($activeTab === 'commands')
                        <!-- Commands Table - Mobile Cards -->
                        <div class="md:hidden">
                            @if($logs->isNotEmpty())
                                <div class="divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                        <div class="p-4 hover:bg-gray-50 transition-colors">
                                            <!-- Card Header -->
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-terminal text-blue-600 text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">Artisan Command</p>
                                                        <p class="text-xs text-gray-500">{{ $log->created_at->format('M d, H:i') }}</p>
                                                    </div>
                                                </div>
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">
                                                    Executed
                                                </span>
                                            </div>

                                            <!-- Command -->
                                            <div class="mb-3">
                                                <code class="block bg-gray-900 text-gray-100 p-3 rounded-lg text-xs font-mono overflow-x-auto">
                                                    php artisan {{ $log->action }}
                                                </code>
                                            </div>

                                            <!-- User Info -->
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium">
                                                        {{ substr($log->user->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $log->user->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-12 text-center text-gray-400">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-terminal text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-700">No command logs found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Commands Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Command</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($logs as $log)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 text-sm text-gray-600">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            <td class="p-4">
                                                <div class="flex items-center space-x-2">
                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium">
                                                        {{ substr($log->user->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $log->user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <code class="bg-gray-900 text-gray-100 px-3 py-1 rounded text-sm font-mono">
                                                    php artisan {{ $log->action }}
                                                </code>
                                            </td>
                                            <td class="p-4">
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">
                                                    Executed
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-12 text-center text-gray-400">No command logs found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Activity Table - Mobile Cards -->
                        <div class="md:hidden">
                            @if($logs->isNotEmpty())
                                <div class="divide-y divide-gray-100">
                                    @foreach($logs as $log)
                                        <div class="p-4 hover:bg-gray-50 transition-colors">
                                            <!-- Card Header -->
                                            <div class="flex justify-between items-start mb-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <span class="text-xs font-medium text-indigo-600">
                                                            {{ substr($log->user->name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $log->created_at->format('M d, H:i') }}</p>
                                                    </div>
                                                </div>
                                                <span class="px-2 py-1 rounded text-xs font-medium 
                                                    {{ in_array($log->action, ['created', 'login']) ? 'bg-green-100 text-green-700' : '' }}
                                                    {{ in_array($log->action, ['deleted', 'logout']) ? 'bg-red-100 text-red-700' : '' }}
                                                    {{ $log->action === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ !$log->action ? 'bg-gray-100 text-gray-700' : '' }}">
                                                    {{ $log->action ?: 'executed' }}
                                                </span>
                                            </div>

                                            <!-- Details -->
                                            <div class="space-y-2">
                                                @if($log->table_name)
                                                    <p class="text-xs text-gray-500 uppercase font-medium">{{ $log->table_name }}</p>
                                                @endif
                                                @if($log->metadata)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($log->metadata as $key => $val)
                                                            <span class="bg-gray-100 px-2 py-1 rounded text-xs text-gray-700">
                                                                {{ $key }}: {{ $val }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-12 text-center text-gray-400">
                                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-history text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-700">No activity logs found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Desktop Activity Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-100">
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Action</th>
                                        <th class="p-4 text-xs font-medium text-gray-500 uppercase">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($logs as $log)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="p-4 text-sm text-gray-600">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            <td class="p-4">
                                                <div class="flex items-center space-x-2">
                                                    <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium">
                                                        {{ substr($log->user->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $log->user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <span class="px-2 py-1 rounded text-xs font-medium 
                                                    {{ in_array($log->action, ['created', 'login']) ? 'bg-green-100 text-green-700' : '' }}
                                                    {{ in_array($log->action, ['deleted', 'logout']) ? 'bg-red-100 text-red-700' : '' }}
                                                    {{ $log->action === 'updated' ? 'bg-blue-100 text-blue-700' : '' }}
                                                    {{ !$log->action ? 'bg-gray-100 text-gray-700' : '' }}">
                                                    {{ $log->action ?: 'executed' }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-sm">
                                                @if($log->type === 'command')
                                                    <code class="bg-gray-900 text-gray-100 px-3 py-1 rounded text-sm font-mono">php artisan {{ $log->action }}</code>
                                                @else
                                                    <div>
                                                        @if($log->table_name)
                                                            <span class="text-xs text-gray-500 uppercase font-medium">{{ $log->table_name }}</span>
                                                        @endif
                                                        @if($log->metadata)
                                                            <div class="flex flex-wrap gap-1 mt-1">
                                                                @foreach($log->metadata as $key => $val)
                                                                    <span class="bg-gray-100 px-2 py-1 rounded text-xs text-gray-700">
                                                                        {{ $key }}: {{ $val }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-12 text-center text-gray-400">No activity logs found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if(($activeTab === 'errors' && $appErrors->hasPages()) || ($activeTab !== 'errors' && $logs->hasPages()))
                    <div class="mt-4">
                        {{ $activeTab === 'errors' ? $appErrors->links() : $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Command Execution Status -->
    @if($commandOutput)
        <div class="fixed bottom-4 right-4 z-50 max-w-md animate-slide-up">
            <div class="bg-gray-800 text-white rounded-lg shadow-xl overflow-hidden">
                <div class="p-3 bg-gray-900 flex justify-between items-center">
                    <span class="text-sm font-medium">Command Output</span>
                    <button wire:click="$set('commandOutput', null)" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4">
                    <pre class="text-xs font-mono text-green-400 overflow-auto max-h-60">{{ $commandOutput }}</pre>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Toast -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 z-50 animate-slide-up">
            <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif
    
<script>
function toggleFilters() {
    const filterSection = document.getElementById('filterSection');
    const filterIcon = document.getElementById('filterIcon');
    
    if (filterSection.classList.contains('hidden')) {
        filterSection.classList.remove('hidden');
        filterIcon.classList.remove('fa-chevron-down');
        filterIcon.classList.add('fa-chevron-up');
    } else {
        filterSection.classList.add('hidden');
        filterIcon.classList.remove('fa-chevron-up');
        filterIcon.classList.add('fa-chevron-down');
    }
}

function toggleCommandModal() {
    // Scroll to custom command input in maintenance tab
    document.querySelector('[wire\\:model="customCommand"]').scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

function clearCache() {
    if (confirm('Clear all cache and optimize the application?')) {
        Livewire.emit('runCommand', 'optimize:clear');
    }
}

// Close filters when clicking outside on mobile
document.addEventListener('click', function(event) {
    const filterSection = document.getElementById('filterSection');
    const filterToggle = document.querySelector('[onclick="toggleFilters()"]');
    
    if (window.innerWidth < 1024 && filterSection && !filterSection.contains(event.target) && 
        !filterToggle.contains(event.target)) {
        if (!filterSection.classList.contains('hidden')) {
            filterSection.classList.add('hidden');
            const filterIcon = document.getElementById('filterIcon');
            filterIcon.classList.remove('fa-chevron-up');
            filterIcon.classList.add('fa-chevron-down');
        }
    }
});
</script>

<style>
/* Hide scrollbar for mobile tabs */
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Animations */
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}

/* Prevent zoom on iOS input focus */
@media screen and (max-width: 768px) {
    input[type="date"],
    input[type="text"],
    select {
        font-size: 16px !important;
    }
}

/* Better touch targets */
@media screen and (max-width: 768px) {
    button, a {
        min-height: 44px;
    }
    
    /* Card hover effects */
    .hover\\:bg-gray-50 {
        transition: background-color 0.2s ease;
    }
}

/* Responsive typography */
@media screen and (max-width: 768px) {
    h1 {
        font-size: 1.25rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
}

/* Better focus states */
button:focus, input:focus, textarea:focus, select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Code blocks */
code {
    font-family: 'Courier New', monospace;
}

/* Command output styling */
pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
</div>
