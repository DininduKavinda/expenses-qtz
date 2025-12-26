<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">GRN Sessions</h1>
            <p class="text-sm text-gray-500">Manage Goods Received Notes</p>
        </div>
        @can('create-grns')
            <a href="{{ route('grns.create') }}" wire:navigate
                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Session</span>
            </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
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
                <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search shop or user..."
                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Quartz</th>
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
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
</div>