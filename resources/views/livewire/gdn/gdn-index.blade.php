<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Goods Despatch Notes') }}
                </h2>
                @can('create-gdns')
                    <a href="{{ route('gdns.create') }}" wire:navigate
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out">
                        Create GDN
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery"
                            placeholder="Search user or remarks..."
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($gdns->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            By</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($gdns as $gdn)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
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
                        <div class="py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-4 opacity-20"></i>
                            <p>No Despatch Notes yet.</p>
                        </div>
                    @endif
                    <div class="mt-4">
                        {{ $gdns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>