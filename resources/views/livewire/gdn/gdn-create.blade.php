<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Create Despatch Note') }}
            </h2>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit="save" class="space-y-8">
                <!-- Basic Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Despatch
                                Date</label>
                            <input type="date" wire:model="gdn_date"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            @error('gdn_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Remarks</label>
                            <input type="text" wire:model="remarks" placeholder="Optional notes..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            @error('remarks') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Items Selection -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800">Select Items to Despatch</h3>
                            <p class="text-xs text-gray-500">Only items from confirmed GRNs are shown</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold">
                            {{ count($selectedItems) }} Selected
                        </span>
                    </div>

                    <div class="p-6">
                        @if($availableItems->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($availableItems as $item)
                                    <label
                                        class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ in_array($item->id, $selectedItems) ? 'border-blue-500 bg-blue-50/50 ring-2 ring-blue-200' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                                                class="h-5 w-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 transition duration-200">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between">
                                                <p class="text-sm font-bold text-gray-900">{{ $item->item->name }}</p>
                                                <span
                                                    class="text-xs font-black text-blue-600">{{ number_format($item->quantity, 0) }}
                                                    {{ $item->item->unit->name ?? 'pcs' }}</span>
                                            </div>
                                            <div class="flex flex-col mt-1">
                                                <span class="text-[10px] text-gray-500 flex items-center">
                                                    <i class="fas fa-shop mr-1 opacity-50"></i>
                                                    {{ $item->grnSession->shop->name }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 flex items-center mt-0.5">
                                                    <i class="fas fa-calendar mr-1 opacity-50"></i>
                                                    {{ $item->grnSession->session_date->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('selectedItems') <p class="text-red-500 text-sm mt-4 text-center">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4 opacity-20"></i>
                                <p>No items available for despatch.</p>
                                <p class="text-xs mt-2">Make sure you have confirmed GRN sessions first.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center space-x-4">
                        <a href="{{ route('gdns.index') }}" wire:navigate
                            class="text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition duration-200 font-bold text-sm">
                            Create Despatch Note
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>