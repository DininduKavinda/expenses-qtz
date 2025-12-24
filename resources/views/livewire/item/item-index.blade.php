<div class="p-4 md:p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="w-full md:w-auto">
            <h1 class="text-2xl font-bold text-gray-800">Items</h1>
            <p class="text-gray-600 mt-1">Manage your inventory items</p>
        </div>

        <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1 sm:flex-none">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" wire:model.live="search" placeholder="Search items..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 text-sm">
            </div>

            <a href="{{ route('items.create') }}" wire:navigate
                class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-4 py-2.5 rounded-lg font-medium shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center space-x-2 text-sm">
                <i class="fas fa-plus text-xs"></i>
                <span>Add Item</span>
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <div class="relative">
                    <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <select wire:model.live="categoryFilter"
                        class="w-full pl-10 pr-4 py-2.5 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 text-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Brand Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <div class="relative">
                    <i
                        class="fas fa-copyright absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <select wire:model.live="brandFilter"
                        class="w-full pl-10 pr-4 py-2.5 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 text-sm">
                        <option value="">All Brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Unit Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <div class="relative">
                    <i
                        class="fas fa-balance-scale absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <select wire:model.live="unitFilter"
                        class="w-full pl-10 pr-4 py-2.5 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 text-sm">
                        <option value="">All Units</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Mobile/Table Header -->
        <div
            class="px-4 py-3 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-semibold text-gray-800">Item List</h2>
                <p class="text-xs text-gray-600 mt-0.5">{{ $items->count() }} items found</p>
            </div>
        </div>

        <!-- Mobile Cards View -->
        <div class="block md:hidden">
            @if ($items->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="h-10 w-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-sm flex-shrink-0">
                                        <i class="fas fa-cube text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">{{ $item->name }}</h3>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @if ($item->category)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500/10 to-pink-500/10 text-purple-700">
                                                    <i class="fas fa-tag mr-1 text-xs"></i>
                                                    {{ $item->category->name }}
                                                </span>
                                            @endif
                                            @if ($item->brand)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-orange-500/10 to-red-500/10 text-orange-700">
                                                    <i class="fas fa-copyright mr-1 text-xs"></i>
                                                    {{ $item->brand->name }}
                                                </span>
                                            @endif
                                            @if ($item->unit)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500/10 to-cyan-500/10 text-blue-700">
                                                    <i class="fas fa-balance-scale mr-1 text-xs"></i>
                                                    {{ $item->unit->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Information -->
                            @if ($item->prices->count() > 0)
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500 mb-1">Prices:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($item->prices->take(2) as $price)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gradient-to-r from-emerald-500/10 to-teal-500/10 text-emerald-700">
                                                {{ $price->price_type }}: ${{ number_format($price->price, 2) }}
                                            </span>
                                        @endforeach
                                        @if ($item->prices->count() > 2)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                                +{{ $item->prices->count() - 2 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center space-x-2 mt-4">
                                <!-- Add this to action buttons section -->
                                <a href="{{ route('items.show', $item->id) }}" wire:navigate
                                    class="bg-gradient-to-r from-emerald-500/10 to-teal-500/10 text-emerald-600 px-3 py-1.5 rounded-lg font-medium hover:from-emerald-500/20 hover:to-teal-500/20 transition-all duration-200 flex items-center space-x-1 text-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('items.edit', $item->id) }}" wire:navigate
                                    class="flex-1 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 text-blue-600 px-3 py-2 rounded-lg font-medium text-sm hover:from-blue-500/20 hover:to-cyan-500/20 transition-all flex items-center justify-center space-x-2">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Edit</span>
                                </a>

                                <button wire:click="confirmDelete({{ $item->id }})"
                                    class="flex-1 bg-gradient-to-r from-red-500/10 to-pink-500/10 text-red-600 px-3 py-2 rounded-lg font-medium text-sm hover:from-red-500/20 hover:to-pink-500/20 transition-all flex items-center justify-center space-x-2">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 px-4 text-center">
                    <div
                        class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cube text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-base font-medium text-gray-700 mb-2">No items found</h3>
                    <p class="text-gray-500 mb-6 text-sm">Get started by creating your first item</p>
                    <a href="{{ route('items.create') }}" wire:navigate
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm hover:shadow transition-all duration-200 inline-flex items-center space-x-2 text-sm">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Add Your First Item</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>ID</span>
                                <i class="fas fa-sort text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>Item Name</span>
                                <i class="fas fa-sort text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>Category</span>
                                <i class="fas fa-sort text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>Brand</span>
                                <i class="fas fa-sort text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>Unit</span>
                                <i class="fas fa-sort text-gray-400 text-xs"></i>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            <div class="flex items-center space-x-1">
                                <span>Prices</span>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                            Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $item)
                        <tr class="hover:bg-gray-50/80 transition-colors border-b border-gray-100 last:border-b-0">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-7 w-7 rounded-md bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-emerald-700">{{ $item->id }}</span>
                                    </div>
                                    <span class="font-medium text-gray-700 text-sm">#{{ $item->id }}</span>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mr-3 shadow-sm flex-shrink-0">
                                        <i class="fas fa-cube text-white text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $item->name }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-4">
                                @if ($item->category)
                                    <div class="flex items-center">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3 shadow-sm flex-shrink-0">
                                            <i class="fas fa-tag text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">{{ $item->category->name }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">— No category —</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if ($item->brand)
                                    <div class="flex items-center">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center mr-3 shadow-sm flex-shrink-0">
                                            <i class="fas fa-copyright text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">{{ $item->brand->name }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">— No brand —</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if ($item->unit)
                                    <div class="flex items-center">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mr-3 shadow-sm flex-shrink-0">
                                            <i class="fas fa-balance-scale text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-sm">{{ $item->unit->name }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">— No unit —</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                @if ($item->prices->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($item->prices->take(3) as $price)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gradient-to-r from-emerald-500/10 to-teal-500/10 text-emerald-700">
                                                {{ $price->price_type }}: ${{ number_format($price->price, 2) }}
                                            </span>
                                        @endforeach
                                        @if ($item->prices->count() > 3)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                                +{{ $item->prices->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">— No prices —</span>
                                @endif
                            </td>

                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('items.show', $item->id) }}" wire:navigate
                                        class="bg-gradient-to-r from-emerald-500/10 to-teal-500/10 text-emerald-600 px-3 py-1.5 rounded-lg font-medium hover:from-emerald-500/20 hover:to-teal-500/20 transition-all duration-200 flex items-center space-x-1 text-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('items.edit', $item->id) }}" wire:navigate
                                        class="bg-gradient-to-r from-blue-500/10 to-cyan-500/10 text-blue-600 px-3 py-1.5 rounded-lg font-medium hover:from-blue-500/20 hover:to-cyan-500/20 transition-all duration-200 flex items-center space-x-1 text-sm">
                                        <i class="fas fa-edit text-xs"></i>
                                        <span>Edit</span>
                                    </a>

                                    <button wire:click="confirmDelete({{ $item->id }})"
                                        class="bg-gradient-to-r from-red-500/10 to-pink-500/10 text-red-600 px-3 py-1.5 rounded-lg font-medium hover:from-red-500/20 hover:to-pink-500/20 transition-all duration-200 flex items-center space-x-1 text-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($items->count() == 0)
                        <tr>
                            <td colspan="7" class="py-12 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <div
                                        class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-cube text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-700 mb-2">No items found</h3>
                                    <p class="text-gray-500 mb-6 text-sm">Get started by creating your first item</p>
                                    <a href="{{ route('items.create') }}" wire:navigate
                                        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm hover:shadow transition-all duration-200 inline-flex items-center space-x-2 text-sm">
                                        <i class="fas fa-plus text-xs"></i>
                                        <span>Add Your First Item</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if ($items->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="text-xs text-gray-600 order-2 sm:order-1">
                        Showing <span class="font-medium">{{ $items->firstItem() ?? 0 }}</span>
                        to <span class="font-medium">{{ $items->lastItem() ?? 0 }}</span>
                        of <span class="font-medium">{{ $items->total() }}</span> items
                    </div>

                    <div class="flex items-center space-x-1 order-1 sm:order-2">
                        {{-- Previous Page Link --}}
                        @if ($items->onFirstPage())
                            <span
                                class="h-8 w-8 rounded-md border border-gray-300 flex items-center justify-center text-gray-400 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <button wire:click="previousPage"
                                class="h-8 w-8 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm transition-colors">
                                <i class="fas fa-chevron-left text-gray-600 text-xs"></i>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($items->links()->elements[0] as $page => $url)
                            @if ($page == $items->currentPage())
                                <button
                                    class="h-8 w-8 rounded-md bg-gradient-to-r from-emerald-500 to-teal-500 text-white flex items-center justify-center shadow-sm text-sm">
                                    <span class="font-medium">{{ $page }}</span>
                                </button>
                            @else
                                <button wire:click="gotoPage({{ $page }})"
                                    class="h-8 w-8 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm transition-colors">
                                    <span class="font-medium text-gray-700">{{ $page }}</span>
                                </button>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($items->hasMorePages())
                            <button wire:click="nextPage"
                                class="h-8 w-8 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50 text-sm transition-colors">
                                <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                            </button>
                        @else
                            <span
                                class="h-8 w-8 rounded-md border border-gray-300 flex items-center justify-center text-gray-400 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
                <!-- Modal Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div
                            class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500/10 to-pink-500/10 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Delete Item</h3>
                            <p class="text-gray-600 text-sm mt-0.5">This action cannot be undone</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <p class="text-gray-700">
                        Are you sure you want to delete this item? All associated prices will be removed permanently.
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
</div>
