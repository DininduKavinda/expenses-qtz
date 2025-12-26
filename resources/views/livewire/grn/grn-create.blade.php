<div class="p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="w-full">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Create New GRN Session</h1>
            <p class="text-sm text-gray-500">Record a new goods received note</p>
        </div>
        <a href="{{ route('grns.index') }}" wire:navigate
            class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Session Info -->
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100">
            <div class="flex items-center gap-2 mb-4 pb-2 border-b">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">Session Details</h2>
            </div>

            <div class="space-y-4">
                <!-- Quartz -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quartz (Work Place)</label>
                    <div class="relative">
                        <select wire:model="quartz_id" disabled
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 appearance-none bg-gray-50">
                            <option value="">{{ Auth::user()->quartz->name }}</option>
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                    </div>
                    @error('quartz_id')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Session Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Session Date</label>
                    <div class="relative">
                        <input type="datetime-local" wire:model="session_date"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('session_date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Shop -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop</label>
                    <div class="relative">
                        <select wire:model.live="shop_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 appearance-none">
                            <option value="">Select Shop</option>
                            @foreach ($allShops as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-store text-gray-400"></i>
                        </div>
                    </div>
                    @error('shop_id')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Participants -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Split Cost Among (Participants)</label>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 max-h-48 overflow-y-auto">
                        @if(count($quartzUsers) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($quartzUsers as $user)
                                    <label
                                        class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-200 hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="checkbox" wire:model="selected_participants" value="{{ $user->id }}"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm text-gray-700">{{ $user->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 text-center py-3">Select a Quartz to see users.</p>
                        @endif
                    </div>
                    @error('selected_participants')
                        <span class="text-red-500 text-xs block mt-2">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Total selected: {{ count($selected_participants) }}</p>
                </div>

                <!-- Bill Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bill Images</label>
                    <div class="text-xs text-gray-500 mb-2">Optional but Recommended</div>

                    @if ($bill_images && count($bill_images) > 0)
                        <!-- Image Preview Grid -->
                        <div class="mb-3">
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($bill_images as $index => $img)
                                    <div class="relative group aspect-square">
                                        <img src="{{ $img->temporaryUrl() }}"
                                            class="h-full w-full object-cover rounded-lg shadow-sm">
                                        <button type="button" wire:click="removeImage({{ $index }})"
                                            class="absolute top-1 right-1 h-6 w-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-xs">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload Area -->
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-400 transition-colors bg-gray-50">
                        <label for="file-upload" class="block cursor-pointer">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                @if(!$bill_images || count($bill_images) === 0)
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-camera text-blue-600"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <span class="text-sm font-medium text-blue-600">Tap to upload</span>
                                    <p class="text-xs text-gray-500 mt-1">or drag & drop</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                            <input id="file-upload" wire:model="bill_images" type="file" class="sr-only" multiple>
                        </label>
                    </div>

                    @if(!$bill_images || count($bill_images) === 0)
                        <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <p class="text-xs text-orange-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Note: If no image is uploaded, session will require confirmation.</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-2 border-b">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Items</h2>
                </div>
                <button type="button" wire:click="addItem"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i>
                    <span>Add Item</span>
                </button>
            </div>

            @if(count($items) === 0)
                <!-- Empty State -->
                <div class="text-center py-8">
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-box-open text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500 mb-2">No items added yet</p>
                    <p class="text-sm text-gray-400">Click "Add Item" to start</p>
                </div>
            @else
                <!-- Items List - Mobile Cards -->
                <div class="space-y-3 md:hidden">
                    @foreach ($items as $index => $item)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" wire:key="mobile-item-{{ $index }}">
                            <!-- Item Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <select wire:model.live="items.{{ $index }}.item_id"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 font-medium">
                                        <option value="">Select Item</option>
                                        @foreach ($allItems as $i)
                                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" wire:click="removeItem({{ $index }})"
                                    class="ml-2 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @error("items.{$index}.item_id")
                                <span class="text-red-500 text-xs block mb-2">{{ $message }}</span>
                            @enderror

                            <!-- Quantity & Price Row -->
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                                    <input type="number" step="0.01" wire:model.live="items.{{ $index }}.quantity"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error("items.{$index}.quantity")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Unit Price</label>
                                    <input type="number" step="0.01" wire:model.live="items.{{ $index }}.unit_price"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error("items.{$index}.unit_price")
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-600">Total:</span>
                                    <span class="text-lg font-bold text-blue-600">
                                        {{ number_format($item['total_price'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($items as $index => $item)
                                <tr wire:key="desktop-item-{{ $index }}" class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3">
                                        <select wire:model.live="items.{{ $index }}.item_id"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Item</option>
                                            @foreach ($allItems as $i)
                                                <option value="{{ $i->id }}">{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                        @error("items.{$index}.item_id")
                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" wire:model.live="items.{{ $index }}.quantity"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error("items.{$index}.quantity")
                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" wire:model.live="items.{{ $index }}.unit_price"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error("items.{$index}.unit_price")
                                            <span class="text-red-500 text-xs block">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-lg font-bold text-blue-600">
                                            {{ number_format($item['total_price'], 2) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @error('items')
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                </div>
            @enderror

            <!-- Summary -->
            @if(count($items) > 0)
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Total Items: {{ count($items) }}</p>
                            <p class="text-xs text-gray-400">All prices are in local currency</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Grand Total</p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ number_format(array_sum(array_column($items, 'total_price')), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div
            class="sticky bottom-4 md:static bg-white md:bg-transparent p-3 md:p-0 rounded-xl shadow-lg md:shadow-none border md:border-0">
            <button type="submit"
                class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-base">
                <i class="fas fa-save"></i>
                <span>Save GRN Session</span>
            </button>
        </div>
    </form>

    <style>
        /* Improve mobile input appearance */
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            right: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        /* Better select appearance on mobile */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Prevent zoom on iOS input focus */
        @media screen and (max-width: 768px) {

            input[type="text"],
            input[type="number"],
            input[type="date"],
            input[type="datetime-local"],
            select {
                font-size: 16px !important;
                /* Prevents iOS zoom */
            }
        }

        /* Custom scrollbar for participants */
        .participants-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .participants-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .participants-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .participants-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</div>