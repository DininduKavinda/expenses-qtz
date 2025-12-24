<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <!-- iOS-style Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <button onclick="history.back()"
                class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">New Item</h1>
        </div>
        <p class="text-gray-600 ml-13">Add a new inventory item</p>
    </div>

    <!-- iOS-style Form Card -->
    <div class="ios-card p-6 md:p-8 shadow-sm mb-8">
        <form wire:submit.prevent="save" class="space-y-8">
            <!-- Basic Information Section -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center space-x-2">
                        <div
                            class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500/10 to-teal-500/10 flex items-center justify-center">
                            <i class="fas fa-info-circle text-emerald-500"></i>
                        </div>
                        <span>Basic Information</span>
                    </h3>
                    <p class="text-gray-600 text-sm mt-1">Enter the basic details of the item</p>
                </div>

                <!-- Name Field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                            <i class="fas fa-cube text-emerald-500 text-sm"></i>
                            <span>Item Name</span>
                        </label>
                        <span class="text-xs text-gray-500">Required</span>
                    </div>

                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-font"></i>
                        </div>
                        <input type="text" wire:model="name" placeholder="e.g., Laptop, Office Chair, Printer"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 focus:bg-white transition-all text-sm"
                            autofocus>
                    </div>

                    @error('name')
                        <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Category and Brand Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Selection -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-tag text-purple-500 text-sm"></i>
                                <span>Category</span>
                            </label>
                            <span class="text-xs text-gray-500">Required</span>
                        </div>

                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <select wire:model="category_id"
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 focus:bg-white transition-all text-sm appearance-none">
                                <option value="">Select a category</option>

                                @php
                                    // Group categories by parent
                                    $parentCategories = $categories->whereNull('parent_id');
                                    $childCategories = $categories->whereNotNull('parent_id');
                                @endphp

                                @foreach ($parentCategories as $parentCategory)
                                    <!-- Parent Category Option -->
                                    <option value="{{ $parentCategory->id }}"
                                        class="font-semibold text-gray-800 bg-gray-100">
                                        {{ $parentCategory->name }}
                                    </option>

                                    <!-- Child Categories -->
                                    @foreach ($childCategories->where('parent_id', $parentCategory->id) as $childCategory)
                                        <option value="{{ $childCategory->id }}" class="pl-6 text-gray-600">
                                            └─ {{ $childCategory->name }}
                                        </option>
                                    @endforeach

                                    <!-- Separator if there are more parent categories -->
                                    @if (!$loop->last)
                                        <option disabled class="h-px bg-gray-200 my-1"></option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        @error('category_id')
                            <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Brand Selection -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                                <i class="fas fa-copyright text-orange-500 text-sm"></i>
                                <span>Brand</span>
                            </label>
                            <span class="text-xs text-gray-400">Optional</span>
                        </div>

                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-copyright"></i>
                            </div>
                            <select wire:model="brand_id"
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 focus:bg-white transition-all text-sm appearance-none">
                                <option value="">Select a brand (optional)</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        @error('brand_id')
                            <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Unit Selection -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                            <i class="fas fa-balance-scale text-blue-500 text-sm"></i>
                            <span>Default Unit of Measurement</span>
                        </label>
                        <span class="text-xs text-gray-500">Required</span>
                    </div>

                    <div class="relative">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-ruler"></i>
                        </div>
                        <select wire:model="unit_id"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm appearance-none">
                            <option value="">Select a default unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>

                    @error('unit_id')
                        <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="space-y-6 mt-3">
                <div class="border-b border-gray-200 pb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-br from-amber-500/10 to-yellow-500/10 flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-amber-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Shop Prices</h3>
                        </div>
                        <button type="button" wire:click="addPrice"
                            class="text-sm text-emerald-600 hover:text-emerald-700 font-medium flex items-center space-x-1">
                            <i class="fas fa-plus"></i>
                            <span>Add Shop Price</span>
                        </button>
                    </div>
                    <p class="text-gray-600 text-sm mt-1">Add price information for different shops</p>
                </div>

                <!-- Shop Price Entries -->
                @foreach ($prices as $index => $price)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 price-section">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-700">Shop Price #{{ $index + 1 }}</span>
                            @if ($index > 0)
                                <!-- Allow removal of additional price entries -->
                                <button type="button" wire:click="removePrice({{ $index }})"
                                    class="text-red-500 hover:text-red-600 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Shop Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shop</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <select wire:model="prices.{{ $index }}.shop_id"
                                        class="w-full pl-12 pr-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
                                        <option value="">Select shop</option>
                                        @foreach ($shops as $shop)
                                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('prices.' . $index . '.shop_id')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit Selection for this price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-balance-scale"></i>
                                    </div>
                                    <select wire:model="prices.{{ $index }}.unit_id"
                                        class="w-full pl-12 pr-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
                                        <option value="">Select unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('prices.' . $index . '.unit_id')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Price Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <input type="number" wire:model="prices.{{ $index }}.price"
                                        step="0.01" min="0" max="999999.99" placeholder="0.00"
                                        class="w-full pl-12 pr-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
                                </div>
                                @error('prices.' . $index . '.price')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="date" wire:model="prices.{{ $index }}.date"
                                        class="w-full pl-12 pr-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-sm">
                                </div>
                                @error('prices.' . $index . '.date')
                                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Price Summary -->
                @if (array_filter($prices, fn($p) => !empty($p['shop_id']) && !empty($p['price'])))
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 border border-emerald-200">
                        <h4 class="font-medium text-emerald-800 mb-3">Price Summary</h4>
                        <div class="space-y-3">
                            @foreach ($prices as $index => $price)
                                @if (!empty($price['shop_id']) && !empty($price['price']))
                                    @php
                                        $shop = $shops->find($price['shop_id']);
                                        $unit = $units->find($price['unit_id'] ?? $price['unit_id']);
                                    @endphp
                                    <div
                                        class="flex items-center justify-between p-3 bg-white rounded-lg border border-emerald-100">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="h-8 w-8 rounded-lg bg-gradient-to-br from-amber-500/10 to-yellow-500/10 flex items-center justify-center">
                                                    <i class="fas fa-store text-amber-500 text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $shop->name ?? 'Unknown Shop' }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $unit->name ?? 'No unit' }} • {{ $price['date'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-emerald-700">
                                                ${{ number_format($price['price'], 2) }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <!-- iOS-style Action Buttons -->
    <div
        class="fixed bottom-20 md:relative md:bottom-0 left-0 right-0 bg-white md:bg-transparent border-t border-gray-200 md:border-0 p-4 md:p-0 shadow-lg md:shadow-none">
        <div class="max-w-4xl mx-auto flex items-center justify-end space-x-3">
            <a href="{{ route('items.index') }}" wire:navigate
                class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>

            <button type="submit" wire:click="save" wire:loading.attr="disabled"
                class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center space-x-2 text-sm">
                <i class="fas fa-check"></i>
                <span wire:loading.remove wire:target="save">Create Item</span>
                <span wire:loading wire:target="save" class="flex items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Creating...</span>
                </span>
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    @if ($processing)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 max-w-xs mx-4 text-center">
                <div
                    class="h-16 w-16 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Creating Item</h3>
                <p class="text-gray-600 text-sm">Please wait a moment...</p>
            </div>
        </div>
    @endif
    <style>
        /* Custom styling for select options */
        select option {
            padding: 8px 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        select option:checked {
            background-color: #d1fae5;
            /* emerald-100 */
            color: #065f46;
            /* emerald-800 */
            font-weight: 500;
        }

        select option[class*="bg-gray-100"] {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #1f2937;
        }

        select option[class*="pl-6"] {
            padding-left: 24px;
        }

        /* iOS-style form animations */
        input:focus,
        select:focus {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }

        /* Hide default select arrow */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Price card styling */
        .price-section {
            transition: all 0.3s ease;
        }

        .price-section:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        // Auto-focus on name field
        document.addEventListener('livewire:initialized', function() {
            const nameInput = document.querySelector('input[wire\\:model="name"]');
            if (nameInput) {
                nameInput.focus();
            }

            // Set default date for price entries
            Livewire.dispatch('set-default-date');
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                e.preventDefault();
                // Cmd/Ctrl + Enter to submit
                Livewire.dispatch('save');
            }

            if (e.key === 'Escape') {
                // Escape to go back
                window.history.back();
            }
        });

        // Auto-format price inputs
        document.addEventListener('livewire:init', () => {
            Livewire.on('set-default-date', () => {
                // Set default date for new price entries
                const today = new Date().toISOString().split('T')[0];
                Livewire.set('prices.0.date', today);
            });
        });

        // Scroll to newly added price field
        document.addEventListener('livewire:updated', function() {
            const priceSections = document.querySelectorAll('.price-section');
            if (priceSections.length > 0) {
                const lastSection = priceSections[priceSections.length - 1];
                lastSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }
        });

        // Auto-select item unit for new price entries
        document.addEventListener('livewire:change', function(event) {
            if (event.detail.component.name === 'item.item-create' && event.detail.name === 'unit_id') {
                // When item unit changes, update unit for all price entries
                const unitId = event.detail.value;
                const priceInputs = document.querySelectorAll(
                    'select[wire\\:model^="prices."][wire\\:model$=".unit_id"]');
                priceInputs.forEach(input => {
                    input.value = unitId;
                });
            }
        });
    </script>
</div>
