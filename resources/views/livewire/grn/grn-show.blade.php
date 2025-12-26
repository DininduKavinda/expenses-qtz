<div class="p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">GRN Session Details</h1>
                <p class="text-sm text-gray-500">View session #{{ $grn->id }} details</p>
            </div>
            <!-- Status Badge - Mobile Only -->
            <div class="md:hidden">
                @if ($grn->confirmed_by !== null)
                    <span
                        class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center gap-1">
                        <i class="fas fa-check-circle text-[10px]"></i>
                        <span>Confirmed</span>
                    </span>
                @elseif($grn->status === 'rejected')
                    <span
                        class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 border border-red-200 flex items-center gap-1">
                        <i class="fas fa-times-circle text-[10px]"></i>
                        <span>Rejected</span>
                    </span>
                @else
                    <span
                        class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 flex items-center gap-1">
                        <i class="fas fa-clock text-[10px]"></i>
                        <span>Pending</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('grns.index') }}" wire:navigate
                class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
            </a>
            <a href="{{ route('grns.edit', $grn) }}" wire:navigate
                class="w-full sm:w-auto px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-pencil-alt"></i>
                <span>Edit</span>
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Session Information Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="px-4 md:px-6 py-4 bg-gray-50 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Session Information</h3>
                    <!-- Status Badge - Desktop Only -->
                    <div class="hidden md:block">
                        @if ($grn->confirmed_by !== null)
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-700 border border-green-200 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>Confirmed</span>
                            </span>
                        @elseif($grn->status === 'rejected')
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-700 border border-red-200 flex items-center gap-2">
                                <i class="fas fa-times-circle"></i>
                                <span>Rejected</span>
                            </span>
                        @else
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200 flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span>Pending Confirmation</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Session Details -->
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <!-- Date -->
                    <div class="space-y-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Date</p>
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $grn->session_date->format('F d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $grn->session_date->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quartz -->
                    <div class="space-y-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Quartz</p>
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-building text-purple-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">{{ $grn->quartz->name }}</p>
                        </div>
                    </div>

                    <!-- Shop -->
                    <div class="space-y-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Shop</p>
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-store text-green-600 text-sm"></i>
                            </div>
                            <p class="font-medium text-gray-800">{{ $grn->shop ? $grn->shop->name : 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div class="space-y-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Created By</p>
                        <div class="flex items-center gap-2">
                            <div
                                class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                {{ substr($grn->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $grn->user->name }}</p>
                                <p class="text-xs text-gray-400">Creator</p>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmed By -->
                    @if($grn->confirmedBy)
                        <div class="col-span-1 md:col-span-2 space-y-1">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Confirmed By</p>
                            <div class="flex items-center gap-2 bg-green-50 p-3 rounded-lg">
                                <div
                                    class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($grn->confirmedBy->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $grn->confirmedBy->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $grn->confirmed_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    @endif

                    <!-- Participants -->
                    <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2 space-y-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Cost Split Among</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($grn->participants as $participant)
                                <div
                                    class="flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-2 rounded-lg border border-blue-100">
                                    <div
                                        class="h-5 w-5 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold">
                                        {{ substr($participant->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm">{{ $participant->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Confirmation Action -->
                @if($grn->confirmed_by === null)
                    <div class="mt-6 pt-4 border-t border-yellow-100">
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <!--
                                                <div class="w-full">
                                                    <label class="block text-xs font-semibold text-yellow-700 uppercase tracking-wider mb-1">
                                                        Select Bank Account for Payment
                                                    </label>
                                                    <select wire:model="selectedBankId"
                                                        class="w-full px-3 py-2 border border-yellow-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm bg-white">
                                                        <option value="">-- Select Bank Account --</option>
                                                        @foreach($bankAccounts as $account)
                                                            <option value="{{ $account->id }}">
                                                                {{ $account->name }} (LKR {{ number_format($account->balance, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('selectedBankId')
                                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                -->
                                <div class="w-full">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-yellow-700">Total Amount:</span>
                                        <span class="text-lg font-bold text-yellow-700">
                                            LKR {{ number_format($grn->grnItems->sum('total_price'), 2) }}
                                        </span>
                                    </div>
                                    <button wire:click="confirmSession"
                                        wire:confirm="Are you sure you want to confirm this GRN session?"
                                        class="w-full px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm transition-colors font-medium flex items-center justify-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Confirm & Pay</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items List Card - Mobile View -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden md:hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Items ({{ $grn->grnItems->count() }})</h3>
                    <span class="text-sm font-bold text-indigo-700">
                        LKR {{ number_format($grn->grnItems->sum('total_price'), 2) }}
                    </span>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach ($grn->grnItems as $item)
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $item->item->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $item->item->code ?? 'No code' }}</p>
                            </div>
                            <span class="text-sm font-bold text-blue-600">
                                LKR {{ number_format($item->total_price, 2) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-gray-50 p-2 rounded">
                                <p class="text-xs text-gray-500">Quantity</p>
                                <p class="font-medium">{{ number_format($item->quantity, 2) }}</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded">
                                <p class="text-xs text-gray-500">Unit Price</p>
                                <p class="font-medium">LKR {{ number_format($item->unit_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Items List Table - Desktop View -->
        <div class="hidden md:block bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
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
                                <td class="px-6 py-4 text-right">LKR {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900">
                                    LKR {{ number_format($item->total_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="3" class="px-6 py-4 text-right text-gray-700">Grand Total</td>
                            <td class="px-6 py-4 text-right text-indigo-700">
                                LKR {{ number_format($grn->grnItems->sum('total_price'), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bill Images Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-4 md:px-6 py-3 bg-gray-50 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-700">Bill Images</h3>
                    @if ($grn->images->count() > 0)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            {{ $grn->images->count() }} images
                        </span>
                    @endif
                </div>
            </div>
            <div class="p-4 md:p-6">
                @if ($grn->images->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($grn->images as $img)
                            <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank"
                                class="block group relative rounded-lg overflow-hidden border border-gray-200 aspect-square">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                    <div
                                        class="bg-white/90 p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-expand text-gray-700"></i>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center py-8 text-gray-400 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-sm">No bill images uploaded</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grand Total Summary - Mobile Only -->
        @if($grn->grnItems->count() > 0)
            <div class="md:hidden sticky bottom-4 bg-indigo-600 text-white rounded-xl shadow-lg p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium">Grand Total</p>
                        <p class="text-xs opacity-90">{{ $grn->grnItems->count() }} items</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold">LKR {{ number_format($grn->grnItems->sum('total_price'), 2) }}</p>
                        @if($grn->confirmed_by === null)
                            <p class="text-xs opacity-90">Pending confirmation</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Improve image viewing on mobile */
        @media screen and (max-width: 768px) {
            .aspect-square {
                aspect-ratio: 1 / 1;
            }

            /* Sticky footer spacing for iOS */
            .sticky.bottom-4 {
                margin-bottom: env(safe-area-inset-bottom, 16px);
            }
        }

        /* Smooth transitions */
        .group-hover\:scale-105 {
            transition-duration: 300ms;
        }

        .group-hover\:opacity-100 {
            transition-duration: 200ms;
            transition-delay: 100ms;
        }

        /* Better focus states for accessibility */
        button:focus,
        a:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Custom scrollbar for tables on mobile */
        @media screen and (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</div>