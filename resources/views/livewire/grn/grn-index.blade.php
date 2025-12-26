<div class="p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="w-full">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">GRN Sessions</h1>
            <p class="text-sm text-gray-500">Manage Goods Received Notes</p>
        </div>
        @can('create-grns')
            <a href="{{ route('grns.create') }}" wire:navigate
                class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Session</span>
            </a>
        @endcan
    </div>

    <!-- Filters - Mobile Collapsible -->
    <div class="mb-6">
        <button onclick="toggleFilters()"
            class="w-full flex items-center justify-between p-3 bg-white rounded-xl shadow-sm border border-gray-100 mb-2">
            <span class="font-medium text-gray-700">
                <i class="fas fa-filter mr-2"></i>
                Filters
            </span>
            <i id="filterIcon" class="fas fa-chevron-down transition-transform"></i>
        </button>

        <div id="filterSection" class="hidden md:block">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date From</label>
                        <input type="date" wire:model.live="dateFrom"
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date To</label>
                        <input type="date" wire:model.live="dateTo"
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Shop</label>
                        <select wire:model.live="selectedShop"
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Shops</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                        <select wire:model.live="selectedStatus"
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search..."
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Table Container - Mobile Cards Design -->
    <div class="md:hidden space-y-4">
        @forelse($grns as $grn)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <!-- Card Header -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $grn->session_date->format('M d, Y') }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $grn->session_date->format('g:i A') }}</div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($grn->status === 'completed') bg-blue-100 text-blue-700 border border-blue-200
                            @elseif($grn->status === 'confirmed') bg-green-100 text-green-700 border border-green-200
                            @elseif($grn->status === 'rejected') bg-red-100 text-red-700 border border-red-200
                            @else bg-yellow-100 text-yellow-700 border border-yellow-200
                            @endif">
                        @if($grn->status === 'completed')
                            <i class="fas fa-check-double text-[10px]"></i>
                        @elseif($grn->status === 'confirmed')
                            <i class="fas fa-check-circle text-[10px]"></i>
                        @elseif($grn->status === 'rejected')
                            <i class="fas fa-times-circle text-[10px]"></i>
                        @else
                            <i class="fas fa-clock text-[10px]"></i>
                        @endif
                        {{ ucfirst($grn->status) }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center">
                        <div class="w-6 mr-2">
                            <i class="fas fa-store text-gray-400"></i>
                        </div>
                        <span class="text-sm text-gray-600">{{ $grn->shop ? $grn->shop->name : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 mr-2">
                            <i class="fas fa-gem text-gray-400"></i>
                        </div>
                        <span class="text-sm text-gray-600">{{ $grn->quartz->name }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-6 mr-2">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold mr-2">
                                {{ substr($grn->user->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-600">{{ $grn->user->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="flex justify-between pt-3 border-t border-gray-100">
                    <div class="flex space-x-4">
                        @can('view-grns')
                            <a href="{{ route('grns.show', $grn) }}" wire:navigate
                                class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endcan
                        @can('update-grns')
                            <a href="{{ route('grns.edit', $grn) }}" wire:navigate
                                class="text-gray-400 hover:text-orange-500 transition-colors" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        @endcan
                    </div>
                    @can('delete-grns')
                        <button wire:click="delete({{ $grn->id }})" wire:confirm="Are you sure?"
                            class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endcan
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-box-open text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-base font-medium">No GRN sessions found</p>
                    <p class="text-sm text-gray-400">Get started by creating a new session.</p>
                </div>
            </div>
        @endforelse

        @if($grns->count() > 0)
            <div class="px-4 py-3">
                {{ $grns->links() }}
            </div>
        @endif
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Quartz</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Shop</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Created By</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($grns as $grn)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $grn->session_date->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $grn->session_date->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                    {{ $grn->quartz->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $grn->shop ? $grn->shop->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($grn->status === 'completed')
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 border border-blue-200 flex items-center w-fit gap-1">
                                        <i class="fas fa-check-double text-[10px]"></i> Completed
                                    </span>
                                @elseif ($grn->status === 'confirmed')
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center w-fit gap-1">
                                        <i class="fas fa-check-circle text-[10px]"></i> Confirmed
                                    </span>
                                @elseif($grn->status === 'rejected')
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 border border-red-200 flex items-center w-fit gap-1">
                                        <i class="fas fa-times-circle text-[10px]"></i> Rejected
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 flex items-center w-fit gap-1">
                                        <i class="fas fa-clock text-[10px]"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold mr-2">
                                        {{ substr($grn->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm text-gray-700">{{ $grn->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @can('view-grns')
                                    <a href="{{ route('grns.show', $grn) }}" wire:navigate
                                        class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan
                                @can('update-grns')
                                    <a href="{{ route('grns.edit', $grn) }}" wire:navigate
                                        class="text-gray-400 hover:text-orange-500 transition-colors" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endcan
                                @can('delete-grns')
                                    <button wire:click="delete({{ $grn->id }})" wire:confirm="Are you sure?"
                                        class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-box-open text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-base font-medium">No GRN sessions found</p>
                                    <p class="text-sm text-gray-400">Get started by creating a new session.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $grns->links() }}
        </div>
    </div>
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

        // Close filters when clicking outside on mobile
        document.addEventListener('click', function (event) {
            const filterSection = document.getElementById('filterSection');
            const filterToggle = document.querySelector('[onclick="toggleFilters()"]');

            if (window.innerWidth < 768 && filterSection && !filterSection.contains(event.target) &&
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
</div>