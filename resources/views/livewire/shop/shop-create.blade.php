<div class="p-4 md:p-6 max-w-xl mx-auto">
    <!-- iOS-style Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <button onclick="history.back()" 
                    class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">New Shop</h1>
        </div>
        <p class="text-gray-600 ml-13">Add a new retail shop</p>
    </div>

    <!-- iOS-style Form Card -->
    <div class="ios-card p-6 md:p-8 shadow-sm mb-8">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Name Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-store text-amber-500 text-sm"></i>
                        <span>Shop Name</span>
                    </label>
                    <span class="text-xs text-gray-500">Required</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-font"></i>
                    </div>
                    <input type="text" 
                           wire:model="name" 
                           placeholder="e.g., Main Store, Downtown Branch, Online Shop"
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 focus:bg-white transition-all text-sm"
                           autofocus>
                </div>

                @error('name')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Location Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-blue-500 text-sm"></i>
                        <span>Location</span>
                    </label>
                    <span class="text-xs text-gray-400">Optional</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <input type="text" 
                           wire:model="location" 
                           placeholder="e.g., 123 Main St, City, Country"
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm">
                </div>

                @error('location')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Additional Information (Optional) -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Additional Information</h4>
                <p class="text-xs text-gray-500">
                    You can add more details like contact information, opening hours, and notes in the edit section after creating the shop.
                </p>
            </div>
        </form>
    </div>

    <!-- iOS-style Action Buttons -->
    <div class="fixed bottom-20 md:relative md:bottom-0 left-0 right-0 bg-white md:bg-transparent border-t border-gray-200 md:border-0 p-4 md:p-0 shadow-lg md:shadow-none">
        <div class="max-w-xl mx-auto flex items-center justify-end space-x-3">
            <a href="{{ route('shops.index') }}" 
               wire:navigate
               class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </a>
            
            <button type="submit" 
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center space-x-2 text-sm">
                <i class="fas fa-check"></i>
                <span wire:loading.remove wire:target="save">Create Shop</span>
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
                <div class="h-16 w-16 rounded-full bg-gradient-to-r from-amber-500 to-yellow-500 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Creating Shop</h3>
                <p class="text-gray-600 text-sm">Please wait a moment...</p>
            </div>
        </div>
    @endif
    <style>
    /* iOS-style form animations */
    input:focus {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }
</style>

<script>
    // Auto-focus on name field
    document.addEventListener('livewire:initialized', function () {
        const nameInput = document.querySelector('input[wire\\:model="name"]');
        if (nameInput) {
            nameInput.focus();
        }
    });
    
    // Handle keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
            e.preventDefault();
            Livewire.dispatch('save');
        }
        
        if (e.key === 'Escape') {
            window.history.back();
        }
    });
</script>
</div>

