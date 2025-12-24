<div class="p-4 md:p-6 max-w-xl mx-auto">
    <!-- iOS-style Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-2">
            <button onclick="history.back()"
                class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left text-gray-600"></i>
            </button>
            <h1 class="text-2xl font-bold text-gray-800">Edit Unit</h1>
        </div>
        <p class="text-gray-600 ml-13">Update unit details</p>
    </div>

    <!-- iOS-style Form Card -->
    <div class="ios-card p-6 md:p-8 shadow-sm mb-8">
        <form wire:submit.prevent="update" class="space-y-6">
            <!-- Name Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <i class="fas fa-balance-scale text-blue-500 text-sm"></i>
                        <span>Unit Name</span>
                    </label>
                    <span class="text-xs text-gray-500">Required</span>
                </div>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-font"></i>
                    </div>
                    <input type="text" wire:model="name" placeholder="e.g., Kilogram, Liter, Piece, Meter"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm"
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
                    <div class="absolute left-4 top-4 text-gray-400">
                        <i class="fas fa-pen"></i>
                    </div>
                    <textarea wire:model="description" placeholder="Add a brief description about this unit..." rows="4"
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white transition-all text-sm resize-none"></textarea>
                </div>

                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-500">Maximum 255 characters</span>
                    @if ($description)
                        <span class="text-xs {{ strlen($description) > 255 ? 'text-red-500' : 'text-gray-500' }}">
                            {{ strlen($description) }}/255
                        </span>
                    @endif
                </div>

                @error('description')
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
        <div class="max-w-xl mx-auto flex items-center justify-between">
            <!-- Delete Button -->
            <button type="button" wire:click="confirmDelete"
                class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center space-x-2 text-sm">
                <i class="fas fa-trash"></i>
                <span>Delete Unit</span>
            </button>

            <!-- Update/Cancel Buttons -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('units.index') }}" wire:navigate
                    class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Cancel</span>
                </a>

                <button type="submit" wire:click="update" wire:loading.attr="disabled"
                    class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed flex items-center space-x-2 text-sm">
                    <i class="fas fa-check"></i>
                    <span wire:loading.remove wire:target="update">Update Unit</span>
                    <span wire:loading wire:target="update" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Updating...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    @if ($processing)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-6 max-w-xs mx-4 text-center">
                <div
                    class="h-16 w-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-spinner fa-spin text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Updating Unit</h3>
                <p class="text-gray-600 text-sm">Please wait a moment...</p>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div
                            class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500/10 to-pink-500/10 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Delete Unit</h3>
                            <p class="text-gray-600 text-sm mt-0.5">This action cannot be undone</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <p class="text-gray-700 mb-4">
                        Are you sure you want to delete "<span class="font-semibold">{{ $name }}</span>"?
                    </p>
                    <p class="text-sm text-gray-600">
                        All associated price records will be affected.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button wire:click="delete"
                        class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 text-white font-medium shadow-sm hover:shadow transition-all text-sm flex items-center space-x-2">
                        <i class="fas fa-trash text-xs"></i>
                        <span>Yes, Delete</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    <style>
        /* iOS-style form animations */
        input:focus,
        textarea:focus {
            transform: translateY(-1px);
            transition: transform 0.2s ease;
        }
    </style>

    <script>
        // Auto-focus on name field
        document.addEventListener('livewire:initialized', function() {
            const nameInput = document.querySelector('input[wire\\:model="name"]');
            if (nameInput) {
                nameInput.focus();
                nameInput.select();
            }
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 's') {
                e.preventDefault();
                Livewire.dispatch('update');
            }

            if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
                Livewire.dispatch('update');
            }

            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</div>
