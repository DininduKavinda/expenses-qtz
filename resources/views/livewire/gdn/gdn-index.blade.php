<div>
    <div class="py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Header -->
            <div class="md:hidden mb-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-lg text-gray-800">Goods Despatch Notes</h2>
                        <p class="text-xs text-gray-500">Manage dispatched goods</p>
                    </div>
                    @can('create-gdns')
                        <a href="{{ route('gdns.create') }}" wire:navigate
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium flex items-center gap-1 shadow-sm">
                            <i class="fas fa-plus text-[10px]"></i>
                            <span>New</span>
                        </a>
                    @endcan
                </div>

                <!-- Mobile Quick Stats -->
                <div class="grid grid-cols-3 gap-2">
                    <div class="bg-blue-50 p-2 rounded-lg text-center">
                        <p class="text-xs text-blue-600 font-medium">Total</p>
                        <p class="text-lg font-bold text-gray-800">{{ $gdns->total() }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg text-center">
                        <p class="text-xs text-gray-600 font-medium">This Month</p>
                        <p class="text-lg font-bold text-gray-800">{{ $gdns->count() }}</p>
                    </div>
                    <div class="bg-green-50 p-2 rounded-lg text-center">
                        <p class="text-xs text-green-600 font-medium">Active</p>
                        <p class="text-lg font-bold text-gray-800">{{ $gdns->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Goods Despatch Notes') }}
                    </h2>
                    <p class="text-sm text-gray-500">Track all dispatched goods</p>
                </div>
                @can('create-gdns')
                    <a href="{{ route('gdns.create') }}" wire:navigate
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Create GDN</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <!-- Mobile Filter Toggle -->
                <button onclick="toggleFilters()"
                    class="md:hidden w-full flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Filters
                    </span>
                    <i id="filterIcon" class="fas fa-chevron-down transition-transform"></i>
                </button>

                <div id="filterSection" class="hidden md:block">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date From</label>
                            <div class="relative">
                                <input type="date" wire:model.live="dateFrom"
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date To</label>
                            <div class="relative">
                                <input type="date" wire:model.live="dateTo"
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="searchQuery"
                                    placeholder="Search user or remarks..."
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pl-10">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    @if($gdns->isNotEmpty())
                        <!-- Mobile Cards View -->
                        <div class="md:hidden space-y-4">
                            @foreach($gdns as $gdn)
                                <div
                                    class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow">
                                    <!-- Card Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold">
                                                {{ substr($gdn->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">{{ $gdn->user->name }}</h3>
                                                <p class="text-xs text-gray-500">{{ $gdn->gdn_date->format('M d, Y h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                            {{ $gdn->gdnItems->count() }} items
                                        </span>
                                    </div>

                                    <!-- Remarks -->
                                    @if($gdn->remarks)
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-500 mb-1">Remarks</p>
                                            <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100">
                                                {{ Str::limit($gdn->remarks, 100) }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Items List -->
                                    <div class="space-y-3">
                                        @php
                                            $groupedBySession = $gdn->gdnItems->groupBy(function ($gi) {
                                                return $gi->grnItem->grnSession->id;
                                            });
                                        @endphp

                                        @foreach($groupedBySession as $sessionId => $items)
                                            @php $session = $items->first()->grnItem->grnSession; @endphp
                                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                <!-- Session Header -->
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="h-6 w-6 rounded-full bg-purple-100 flex items-center justify-center">
                                                            <i class="fas fa-store text-purple-600 text-[10px]"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-medium text-gray-700">{{ $session->shop->name }}
                                                            </p>
                                                            <p class="text-[10px] text-gray-400">
                                                                {{ $session->session_date->format('M d') }}</p>
                                                        </div>
                                                    </div>
                                                    <span class="text-[10px] bg-white text-gray-600 px-2 py-1 rounded-full border">
                                                        {{ $items->count() }} types
                                                    </span>
                                                </div>

                                                <!-- Items Grid -->
                                                <div class="grid grid-cols-2 gap-2">
                                                    @foreach($items as $gdnItem)
                                                        <div class="bg-white p-2 rounded border border-gray-100">
                                                            <p class="text-xs font-medium text-gray-900 truncate">
                                                                {{ $gdnItem->grnItem->item->name }}
                                                            </p>
                                                            <div class="flex justify-between items-center mt-1">
                                                                <span class="text-xs text-gray-500">Qty:</span>
                                                                <span class="text-xs font-bold text-blue-600">
                                                                    {{ number_format($gdnItem->quantity, 0) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Total Summary -->
                                    <div class="mt-4 pt-3 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <div class="text-sm text-gray-600">
                                                Total Items: <span
                                                    class="font-bold">{{ $gdn->gdnItems->sum('quantity') }}</span>
                                            </div>
                                            <button onclick="toggleDetails('details-{{ $gdn->id }}')"
                                                class="text-xs text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                                <span>View Details</span>
                                                <i class="fas fa-chevron-down text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Expandable Details (Hidden by default) -->
                                    <div id="details-{{ $gdn->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500">Created:</span>
                                                <span class="font-medium">{{ $gdn->created_at->format('M d, Y h:i A') }}</span>
                                            </div>
                                            @if($gdn->updated_at != $gdn->created_at)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500">Last Updated:</span>
                                                    <span class="font-medium">{{ $gdn->updated_at->format('M d, Y h:i A') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            By
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($gdns as $gdn)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $gdn->gdn_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $gdn->gdn_date->format('g:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-7 w-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-bold mr-2">
                                                        {{ substr($gdn->user->name, 0, 1) }}
                                                    </div>
                                                    <span>{{ $gdn->user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div class="space-y-2">
                                                    @php
                                                        $groupedBySession = $gdn->gdnItems->groupBy(function ($gi) {
                                                            return $gi->grnItem->grnSession->id;
                                                        });
                                                    @endphp
                                                    @foreach($groupedBySession as $sessionId => $items)
                                                        @php $session = $items->first()->grnItem->grnSession; @endphp
                                                        <div class="bg-gray-50 rounded p-2 border border-gray-100">
                                                            <div
                                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
                                                                FROM: {{ $session->shop->name }}
                                                                ({{ $session->session_date->format('M d') }})
                                                            </div>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach($items as $gdnItem)
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-white text-indigo-700 border border-indigo-100 shadow-sm">
                                                                        {{ $gdnItem->grnItem->item->name }}
                                                                        ({{ number_format($gdnItem->quantity, 0) }})
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <p class="max-w-xs truncate" title="{{ $gdn->remarks }}">
                                                    {{ $gdn->remarks ?: '-' }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="py-12 text-center text-gray-500">
                            <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-base font-medium text-gray-700 mb-2">No Despatch Notes yet</p>
                            <p class="text-sm text-gray-400 mb-6">Get started by creating your first GDN</p>
                            @can('create-gdns')
                                <a href="{{ route('gdns.create') }}" wire:navigate
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create First GDN
                                </a>
                            @endcan
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if($gdns->isNotEmpty())
                        <div class="mt-4">
                            {{ $gdns->links() }}
                        </div>
                    @endif
                </div>
            </div>
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

        function toggleDetails(id) {
            const details = document.getElementById(id);
            const icon = details.previousElementSibling.querySelector('i');

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                details.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
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

    <style>
        /* Prevent zoom on iOS input focus */
        @media screen and (max-width: 768px) {

            input[type="date"],
            input[type="text"],
            select {
                font-size: 16px !important;
            }
        }

        /* Smooth transitions */
        .hidden {
            transition: opacity 0.2s ease-out, height 0.2s ease-out;
        }

        /* Better touch targets */
        @media screen and (max-width: 768px) {

            button,
            a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Custom scrollbar for mobile */
        @media screen and (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</div>