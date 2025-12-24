<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">GRN Session Details</h1>
            <p class="text-sm text-gray-500">View session #{{ $grn->id }} details</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('grns.index') }}" wire:navigate
                class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <a href="{{ route('grns.edit', $grn) }}" wire:navigate
                class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                <i class="fas fa-pencil-alt mr-2"></i> Edit
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Details Card -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Session Information</h3>
                    @if ($grn->status === 'confirmed')
                        <span
                            class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Confirmed
                        </span>
                    @elseif($grn->status === 'rejected')
                        <span
                            class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-700 border border-red-200 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    @else
                        <span
                            class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 flex items-center gap-2">
                            <i class="fas fa-clock"></i> Pending Confirmation
                        </span>
                    @endif
                </div>
                <div class="p-6 grid grid-cols-2 gap-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Date</p>
                        <p class="font-medium text-gray-800">{{ $grn->session_date->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $grn->session_date->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Quartz</p>
                        <p class="font-medium text-gray-800">{{ $grn->quartz->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Shop</p>
                        <p class="font-medium text-gray-800">{{ $grn->shop ? $grn->shop->name : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Created By</p>
                        <div class="flex items-center mt-1">
                            <div
                                class="h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">
                                {{ substr($grn->user->name, 0, 1) }}
                            </div>
                            <p class="font-medium text-gray-800">{{ $grn->user->name }}</p>
                        </div>
                    </div>
                    @if($grn->confirmedBy)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Confirmed By</p>
                            <div class="flex items-center mt-1">
                                <div
                                    class="h-6 w-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold mr-2">
                                    {{ substr($grn->confirmedBy->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $grn->confirmedBy->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $grn->confirmed_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Confirmation Action -->
                @if($grn->status !== 'confirmed')
                    <div class="px-6 py-4 bg-yellow-50/50 border-t border-yellow-100 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Confirmation Required</p>
                            <p class="text-xs text-yellow-600">This session has not been confirmed yet.</p>
                        </div>
                        <button wire:click="confirmSession"
                            wire:confirm="Are you sure you want to confirm this GRN session?"
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm transition-colors text-sm font-medium">
                            <i class="fas fa-check mr-1"></i> Confirm Session
                        </button>
                    </div>
                @endif
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Items List</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3">Item Name</th>
                                <th class="px-6 py-3 text-right">Quantity</th>
                                <th class="px-6 py-3 text-right">Unit Price</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($grn->grnItems as $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->item->name }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                                        {{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="3" class="px-6 py-4 text-right text-gray-700">Grand Total</td>
                                <td class="px-6 py-4 text-right text-indigo-700">
                                    {{ number_format($grn->grnItems->sum('total_price'), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar / Images -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Bill Images</h3>
                </div>
                <div class="p-6">
                    @if ($grn->images->count() > 0)
                        <div class="grid grid-cols-1 gap-4">
                            @foreach ($grn->images as $img)
                                <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank"
                                    class="block group relative rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                        class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <i
                                            class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-2xl drop-shadow-lg"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="text-sm">No bill images uploaded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>