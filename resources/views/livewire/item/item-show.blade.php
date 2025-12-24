<div class="p-4 md:p-6 max-w-7xl mx-auto" x-data="{ activeTab: 'details' }">
    <!-- iOS-style Header -->
    <div class="grid grid-cols-2 mb-6">
        <div class="col-span-1 md:col-span-1">
            <div class="flex items-center space-x-3 ">
                <button onclick="history.back()"
                    class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <i class="fas fa-chevron-left text-gray-600"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $item->name }}</h1>
                    <p class="text-gray-600 text-sm mt-0.5">Item ID: #{{ $item->id }}</p>
                </div>
            </div>
        </div>

        <!-- Item Overview Card -->
        <div class="ios-card col-span-1 md:col-span-1 shadow-sm">
            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mb-8">
                <a href="{{ route('items.edit', $item->id) }}" wire:navigate
                    class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm hover:shadow transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit</span>
                </a>

                <a href="{{ route('items.index') }}" wire:navigate
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors flex items-center space-x-2">
                    <i class="fas fa-list text-sm"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'details'"
                :class="{
                    'border-emerald-500 text-emerald-600': activeTab === 'details',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'details'
                }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2 transition-colors">
                <i class="fas fa-info-circle"></i>
                <span>Information</span>
            </button>

            <button @click="activeTab = 'prices'"
                :class="{
                    'border-emerald-500 text-emerald-600': activeTab === 'prices',
                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prices'
                }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2 transition-colors">
                <i class="fas fa-dollar-sign"></i>
                <span>Prices</span>
                @if ($item->prices->count() > 0)
                    <span class="bg-emerald-100 text-emerald-600 text-xs font-medium px-2 py-0.5 rounded-full">
                        {{ $item->prices->count() }}
                    </span>
                @endif
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div>
        <!-- Information Tab -->
        <div x-show="activeTab === 'details'" x-transition>
            <div class="space-y-8">
                <!-- Basic Info Card -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Item Name</p>
                                <p class="text-gray-800 mt-1 text-lg">{{ $item->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Category</p>
                                @if ($item->category)
                                    <div class="flex items-center space-x-2 mt-1">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                            <i class="fas fa-tag text-white text-xs"></i>
                                        </div>
                                        <p class="text-gray-800">{{ $item->category->name }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-400 italic mt-1">No category assigned</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Brand</p>
                                @if ($item->brand)
                                    <div class="flex items-center space-x-2 mt-1">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                                            <i class="fas fa-copyright text-white text-xs"></i>
                                        </div>
                                        <p class="text-gray-800">{{ $item->brand->name }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-400 italic mt-1">No brand assigned</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Unit</p>
                                @if ($item->unit)
                                    <div class="flex items-center space-x-2 mt-1">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                                            <i class="fas fa-balance-scale text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-gray-800">{{ $item->unit->name }}</p>
                                            @if ($item->unit->description)
                                                <p class="text-sm text-gray-500 mt-1">{{ $item->unit->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-400 italic mt-1">No unit assigned</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata Card -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="font-medium text-gray-800 mb-4">Additional Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Created Date</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <p class="text-gray-800">{{ $item->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Last Updated</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <i class="fas fa-clock text-gray-400"></i>
                                <p class="text-gray-800">{{ $item->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prices Tab -->
        <div x-show="activeTab === 'prices'" x-transition>
            @if ($item->prices->count() > 0)
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Price History</h3>
                            <p class="text-gray-600 text-sm mt-1">Price records across different shops</p>
                        </div>
                    </div>

                    <!-- Mobile Price Cards -->
                    <div class="block md:hidden space-y-4">
                        @foreach ($item->prices->groupBy('shop_id') as $shopPrices)
                            @php
                                $shop = $shopPrices->first()->shop;
                                $latestPrice = $shopPrices->first();
                            @endphp
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="h-10 w-10 rounded-lg bg-gradient-to-br from-amber-500 to-yellow-500 flex items-center justify-center">
                                            <i class="fas fa-store text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $shop->name }}</h4>
                                            @if ($shop->location)
                                                <p class="text-xs text-gray-500">{{ $shop->location }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-emerald-600">
                                            ${{ number_format($latestPrice->price, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $latestPrice->date }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Price Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th
                                        class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                                        Shop
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                                        Price
                                    </th>
                                    <th
                                        class="text-left py-3 px-4 font-semibold text-gray-700 text-sm border-b border-gray-200">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->prices as $price)
                                    <tr
                                        class="hover:bg-gray-50/80 transition-colors border-b border-gray-100 last:border-b-0">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-9 w-9 rounded-lg bg-gradient-to-br from-amber-500 to-yellow-500 flex items-center justify-center mr-3 shadow-sm">
                                                    <i class="fas fa-store text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800 text-sm">
                                                        {{ $price->shop->name }}</p>
                                                    @if ($price->shop->location)
                                                        <p class="text-xs text-gray-500">
                                                            {{ $price->shop->location }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-9 w-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mr-3 shadow-sm">
                                                    <i class="fas fa-dollar-sign text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xl font-bold text-emerald-600">
                                                        ${{ number_format($price->price, 2) }}</p>
                                                    @if ($price->unit)
                                                        <p class="text-xs text-gray-500">per
                                                            {{ $price->unit->name }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-9 w-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3 shadow-sm">
                                                    <i class="fas fa-calendar text-white text-xs"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800 text-sm">
                                                        {{ $price->date }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($price->date)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                        <h4 class="font-medium text-gray-800 mb-4">Price Summary</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-500">Average Price</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">
                                    ${{ number_format($item->prices->avg('price'), 2) }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-500">Highest Price</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">
                                    ${{ number_format($item->prices->max('price'), 2) }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-500">Lowest Price</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">
                                    ${{ number_format($item->prices->min('price'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div
                        class="h-16 w-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-dollar-sign text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No price records found</h3>
                    <p class="text-gray-500 mb-6">This item doesn't have any price information yet.</p>
                    <a href="{{ route('items.edit', $item->id) }}" wire:navigate
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm hover:shadow transition-all duration-200 inline-flex items-center space-x-2">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add Prices</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <style>
    /* Smooth tab transitions */
    [x-show] {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card hover effects */
    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<script>
    // Handle keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Escape to go back
        if (e.key === 'Escape') {
            window.history.back();
        }
    });
</script>
</div>
