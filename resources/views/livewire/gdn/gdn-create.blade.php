<div>
    <div class="py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Header -->
            <div class="md:hidden mb-6 space-y-4">
                <div>
                    <a href="{{ route('gdns.index') }}" wire:navigate
                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 mb-3">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Back to GDN</span>
                    </a>
                    <h2 class="font-semibold text-xl text-gray-800">Create Despatch Note</h2>
                    <p class="text-sm text-gray-500">Select items to dispatch</p>
                </div>
                
                <!-- Mobile Progress Steps -->
                <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">
                            1
                        </div>
                        <span class="text-xs font-medium text-gray-700">Details</span>
                    </div>
                    <div class="h-px w-8 bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs">
                            2
                        </div>
                        <span class="text-xs text-gray-500">Items</span>
                    </div>
                    <div class="h-px w-8 bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-xs">
                            3
                        </div>
                        <span class="text-xs text-gray-500">Review</span>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:block mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Create Despatch Note') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Select items from confirmed GRN sessions to dispatch</p>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <form wire:submit="save" class="space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Basic Information</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Despatch Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Despatch Date <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" wire:model="gdn_date"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                            </div>
                            @error('gdn_date')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Remarks <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <div class="relative">
                                <textarea wire:model="remarks" rows="3" placeholder="Add notes about this despatch..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"></textarea>
                                <div class="absolute right-3 top-3 pointer-events-none">
                                    <i class="fas fa-comment text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Character count: {{ strlen($remarks) }}/500</p>
                            @error('remarks')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Items Selection Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-4 md:px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-boxes text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">Select Items to Despatch</h3>
                                    <p class="text-xs text-gray-500">Only items from confirmed GRNs are shown</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">
                                    {{ count($selectedItems) }} Selected
                                </span>
                                @if(count($selectedItems) > 0)
                                    <button type="button" wire:click="clearSelection"
                                        class="text-xs text-red-600 hover:text-red-700 flex items-center gap-1">
                                        <i class="fas fa-times"></i>
                                        <span>Clear</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Items Content -->
                    <div class="p-4 md:p-6">
                        @if($availableItems->isNotEmpty())
                            <!-- Mobile View - Accordion -->
                            <div class="md:hidden space-y-4">
                                @foreach($availableItems as $item)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <label class="flex items-center p-3 bg-white">
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                                                class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900">{{ $item->item->name }}</p>
                                                        <p class="text-xs text-gray-500">Code: {{ $item->item->code ?? 'N/A' }}</p>
                                                    </div>
                                                    <span class="text-sm font-bold text-blue-600">
                                                        {{ number_format($item->quantity, 0) }} {{ $item->item->unit->name ?? 'pcs' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <!-- Item Details - Expandable -->
                                        <div class="border-t border-gray-100 p-3 bg-gray-50">
                                            <div class="grid grid-cols-2 gap-2 text-xs">
                                                <div class="flex items-center gap-2">
                                                    <div class="h-4 w-4 rounded-full bg-purple-100 flex items-center justify-center">
                                                        <i class="fas fa-store text-purple-600 text-[10px]"></i>
                                                    </div>
                                                    <span class="text-gray-600">{{ $item->grnSession->shop->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="h-4 w-4 rounded-full bg-orange-100 flex items-center justify-center">
                                                        <i class="fas fa-calendar text-orange-600 text-[10px]"></i>
                                                    </div>
                                                    <span class="text-gray-600">{{ $item->grnSession->session_date->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="h-4 w-4 rounded-full bg-green-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-green-600 text-[10px]"></i>
                                                    </div>
                                                    <span class="text-gray-600">{{ $item->grnSession->user->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="h-4 w-4 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-box text-blue-600 text-[10px]"></i>
                                                    </div>
                                                    <span class="text-gray-600">Available: {{ number_format($item->quantity, 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Desktop View - Grid -->
                            <div class="hidden md:grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                @foreach($availableItems as $item)
                                    <label class="relative flex items-start p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 {{ in_array($item->id, $selectedItems) ? 'border-blue-500 bg-blue-50/50 ring-2 ring-blue-200' : 'border-gray-100 hover:border-gray-200 hover:bg-gray-50' }}">
                                        <div class="flex items-center h-5 mt-0.5">
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}"
                                                class="h-5 w-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 transition duration-200">
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-gray-900">{{ $item->item->name }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">Code: {{ $item->item->code ?? 'N/A' }}</p>
                                                </div>
                                                <span class="text-sm font-bold text-blue-600">
                                                    {{ number_format($item->quantity, 0) }} {{ $item->item->unit->name ?? 'pcs' }}
                                                </span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 mt-3 text-xs">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-store text-purple-500 text-[10px]"></i>
                                                    <span class="text-gray-600 truncate" title="{{ $item->grnSession->shop->name }}">
                                                        {{ $item->grnSession->shop->name }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-calendar text-orange-500 text-[10px]"></i>
                                                    <span class="text-gray-600">{{ $item->grnSession->session_date->format('M d') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-user text-green-500 text-[10px]"></i>
                                                    <span class="text-gray-600 truncate" title="{{ $item->grnSession->user->name }}">
                                                        {{ $item->grnSession->user->name }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-box text-blue-500 text-[10px]"></i>
                                                    <span class="text-gray-600">Available: {{ number_format($item->quantity, 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="py-12 text-center text-gray-500">
                                <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-base font-medium text-gray-700 mb-2">No items available for despatch</p>
                                <p class="text-sm text-gray-400 mb-6">Make sure you have confirmed GRN sessions first</p>
                                <a href="{{ route('grns.index') }}" wire:navigate
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Check GRN Sessions
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Selected Items Summary - Mobile Only -->
                    @if(count($selectedItems) > 0)
                        <div class="md:hidden sticky bottom-0 bg-white border-t border-gray-200 shadow-lg">
                            <div class="p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">{{ count($selectedItems) }} items selected</p>
                                        <p class="text-xs text-gray-500">Tap to review</p>
                                    </div>
                                    <button type="button" onclick="scrollToReview()"
                                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">
                                        Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions Footer -->
                    <div class="px-4 md:px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="w-full sm:w-auto">
                                <a href="{{ route('gdns.index') }}" wire:navigate
                                    class="w-full sm:w-auto px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 font-medium flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i>
                                    <span>Cancel</span>
                                </a>
                            </div>
                            
                            <div class="w-full sm:w-auto flex flex-col items-end">
                                @if(count($selectedItems) > 0)
                                    <div class="text-right mb-2">
                                        <p class="text-xs text-gray-600">Creating GDN with</p>
                                        <p class="text-sm font-bold text-green-600">{{ count($selectedItems) }} selected items</p>
                                    </div>
                                @endif
                                <button type="submit"
                                    class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all duration-200 font-bold text-sm flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ count($selectedItems) === 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Create Despatch Note</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
<script>
function scrollToReview() {
    document.querySelector('button[type="submit"]').scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
}

// Character counter for remarks
document.addEventListener('DOMContentLoaded', function() {
    const remarksTextarea = document.querySelector('textarea[name="remarks"]');
    if (remarksTextarea) {
        remarksTextarea.addEventListener('input', function() {
            const counter = this.nextElementSibling;
            if (counter && counter.classList.contains('character-counter')) {
                counter.textContent = `Character count: ${this.value.length}/500`;
            }
        });
    }
});

// Prevent zoom on iOS input focus
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 768) {
        const inputs = document.querySelectorAll('input[type="text"], input[type="date"], textarea');
        inputs.forEach(input => {
            input.style.fontSize = '16px';
        });
    }
});

// Add character counter to remarks textarea
document.addEventListener('livewire:init', function() {
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(() => {
            // Update character counter after Livewire updates
            const remarksTextarea = document.querySelector('textarea[wire\\:model="remarks"]');
            if (remarksTextarea) {
                const counter = remarksTextarea.parentElement.querySelector('.character-counter');
                if (!counter) {
                    const counterElement = document.createElement('p');
                    counterElement.className = 'text-xs text-gray-500 mt-1 character-counter';
                    counterElement.textContent = `Character count: ${remarksTextarea.value.length}/500`;
                    remarksTextarea.parentElement.appendChild(counterElement);
                }
            }
        });
    });
});
</script>

<style>
/* Prevent zoom on iOS input focus */
@media screen and (max-width: 768px) {
    input[type="date"],
    input[type="text"],
    textarea {
        font-size: 16px !important;
    }
}

/* Sticky footer spacing for iOS */
@media screen and (max-width: 768px) {
    .sticky.bottom-0 {
        margin-bottom: env(safe-area-inset-bottom, 0);
        padding-bottom: env(safe-area-inset-bottom, 12px);
    }
}

/* Better checkbox styling for mobile */
@media screen and (max-width: 768px) {
    input[type="checkbox"] {
        min-height: 44px;
        min-width: 44px;
    }
    
    /* Ensure touch targets are large enough */
    label {
        min-height: 44px;
    }
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Custom scrollbar for mobile */
@media screen and (max-width: 768px) {
    .overflow-y-auto {
        -webkit-overflow-scrolling: touch;
    }
}

/* Better focus states for accessibility */
button:focus, input:focus, textarea:focus, select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Loading state for submit button */
button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
</div>
