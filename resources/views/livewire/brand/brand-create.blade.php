<div class="p-4 md:p-6 max-w-xl mx-auto">
    <!-- iOS-style Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <button onclick="history.back()"
                class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">New Brand</h1>
        </div>
        <p class="text-gray-600 ml-13">Create a new product brand</p>
    </div>

    <!-- iOS-style Form Card -->
    <div class="ios-card p-6 md:p-8 shadow-sm mb-8">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Name Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-copyright text-orange-500 text-sm"></i>
                        <span>Brand Name</span>
                    </label>
                    <span class="text-xs text-gray-500">Required</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-font"></i>
                    </div>
                    <input type="text" wire:model="name" placeholder="e.g., Apple, Nike, Samsung"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 focus:bg-white transition-all text-sm"
                        autofocus>
                </div>

                @error('name')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Category Selection with Grouping -->
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
                            <option value="{{ $parentCategory->id }}" class="font-semibold text-gray-800 bg-gray-100">
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
        </form>
    </div>

    <!-- iOS-style Action Buttons -->
    <div
        class="fixed bottom-20 md:relative md:bottom-0 left-0 right-0 bg-white md:bg-transparent border-t border-gray-200 md:border-0 p-4 md:p-0 shadow-lg md:shadow-none">
        <div class="max-w-xl mx-auto flex items-center justify-end space-x-3">
            <a href="{{ route('brands.index') }}" wire:navigate
                class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>

            <button type="submit" wire:click="save"
                class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center space-x-2 text-sm">
                <i class="fas fa-check"></i>
                <span>Create Brand</span>
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    @if ($processing)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 max-w-xs mx-4 text-center">
                <div
                    class="h-16 w-16 rounded-full bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Creating Brand</h3>
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
            background-color: #fef3c7;
            /* orange-50 */
            color: #7c2d12;
            /* orange-900 */
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
        textarea:focus,
        select:focus {
            transform: translateY(-1px);
        }

        /* Hide default select arrow in some browsers */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>

    <script>
        // Auto-focus on name field
        document.addEventListener('livewire:initialized', function() {
            const nameInput = document.querySelector('input[wire\\:model="name"]');
            if (nameInput) {
                nameInput.focus();
            }
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                // Cmd/Ctrl + Enter to submit
                Livewire.dispatch('save');
            }

            if (e.key === 'Escape') {
                // Escape to go back
                window.history.back();
            }
        });

        // Character counter for description
        document.addEventListener('livewire:init', () => {
            Livewire.on('description-updated', (value) => {
                if (value.length > 500) {
                    // Optional: trim the text or show warning
                    console.log('Description too long');
                }
            });
        });
    </script>
</div>
