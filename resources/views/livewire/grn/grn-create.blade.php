<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Create New GRN Session</h1>
            <p class="text-sm text-gray-500">Record a new goods received note</p>
        </div>
        <a href="{{ route('grns.index') }}" wire:navigate
            class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Session Info -->
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Session Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quartz (Work Place)</label>
                    <select wire:model="quartz_id" disabled
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">{{ Auth::user()->quartz->name }}</option>
                    </select>
                    @error('quartz_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Session Date</label>
                    <input type="datetime-local" wire:model="session_date"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('session_date')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shop</label>
                    <select wire:model.live="shop_id"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Shop</option>
                        @foreach ($allShops as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('shop_id')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bill Images (Optional but
                        Recommended)</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors bg-gray-50">
                        <div class="space-y-1 text-center">
                            @if ($bill_images)
                                <div class="flex flex-wrap gap-2 justify-center mb-2">
                                    @foreach ($bill_images as $img)
                                        <div class="relative group">
                                            <img src="{{ $img->temporaryUrl() }}"
                                                class="h-24 w-24 object-cover rounded-lg shadow-sm">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload files</span>
                                    <input id="file-upload" wire:model="bill_images" type="file" class="sr-only"
                                        multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            <p class="text-xs text-orange-500 mt-2 font-medium">Note: If no image is uploaded, session
                                will
                                require confirmation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold text-gray-800">Items</h2>
                <button type="button" wire:click="addItem"
                    class="text-sm px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Item
                </button>
            </div>

            <div class="space-y-4">
                @foreach ($items as $index => $item)
                    <div class="flex flex-col md:flex-row gap-4 items-end bg-gray-50/50 p-4 rounded-lg border border-gray-200"
                        wire:key="item-{{ $index }}">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Item</label>
                            <select wire:model.live="items.{{ $index }}.item_id"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Item</option>
                                @foreach ($allItems as $i)
                                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                            @error("items.{$index}.item_id")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full md:w-32">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                            <input type="number" step="0.01" wire:model.live="items.{{ $index }}.quantity"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error("items.{$index}.quantity")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full md:w-32">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Unit Price</label>
                            <input type="number" step="0.01" wire:model.live="items.{{ $index }}.unit_price"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error("items.{$index}.unit_price")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full md:w-32">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                            <input type="text" readonly value="{{ number_format($item['total_price'], 2) }}"
                                class="w-full rounded-lg border-gray-200 bg-gray-100 text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        <button type="button" wire:click="removeItem({{ $index }})"
                            class="mb-1 p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Remove Item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            @error('items')
                <span class="text-red-500 text-sm block mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:translate-y-[-1px] transition-all">
                <i class="fas fa-save mr-2"></i> Save GRN Session
            </button>
        </div>
    </form>
</div>