<div class="p-4 md:p-6 max-w-xl mx-auto">
    <!-- iOS-style Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <button onclick="history.back()" 
                    class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quartz->name }}</h1>
                <p class="text-gray-600 text-sm mt-0.5">Quartz ID: #{{ $quartz->id }}</p>
            </div>
        </div>
    </div>

    <!-- iOS-style Form Card -->
    <div class="ios-card p-6 md:p-8 shadow-sm mb-8">
        <!-- Quick Stats -->
        

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Name Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-gem text-purple-500 text-sm"></i>
                        <span>Quartz Name</span>
                    </label>
                    <span class="text-xs text-gray-500">Required</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-font"></i>
                    </div>
                    <input type="text" 
                           wire:model="name" 
                           placeholder="Enter quartz name"
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 focus:bg-white transition-all text-sm"
                           autofocus>
                </div>

                @error('name')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <!-- Description Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-align-left text-blue-500 text-sm"></i>
                        <span>Description</span>
                    </label>
                    <span class="text-xs text-gray-400">Optional</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-5 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-edit"></i>
                    </div>
                    <textarea 
                        wire:model="description" 
                        rows="4"
                        placeholder="Enter quartz description..."
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm resize-none"></textarea>
                </div>

                @error('description')
                    <div class="flex items-center space-x-2 mt-2 text-red-500 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Max 500 characters</span>
                    <span>{{ strlen($description) }}/500</span>
                </div>
            </div>

        </form>
    </div>

    <!-- iOS-style Action Buttons -->
    <div class="fixed bottom-20 md:relative md:bottom-0 left-0 right-0 bg-white md:bg-transparent border-t border-gray-200 md:border-0 p-4 md:p-0 shadow-lg md:shadow-none">
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <a href="{{ route('quartzs.index') }}" 
               wire:navigate
               class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
            
            <div class="flex items-center space-x-3">
                <button type="button" 
                        onclick="history.back()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </button>
                
                <button type="submit" 
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center space-x-2 text-sm">
                    <i class="fas fa-save"></i>
                    <span wire:loading.remove wire:target="save">Save Changes</span>
                    <span wire:loading wire:target="save" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Saving...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    @if ($processing)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 max-w-xs mx-4 text-center">
                <div class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Updating Quartz</h3>
                <p class="text-gray-600 text-sm">Please wait a moment...</p>
            </div>
        </div>
    @endif
    <style>
    /* iOS-style form animations */
    input:focus, textarea:focus {
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
        if ((e.metaKey || e.ctrlKey) && e.key === 's') {
            e.preventDefault();
            Livewire.dispatch('save');
        }
        
        if (e.key === 'Escape') {
            window.history.back();
        }
    });
</script>
</div>

