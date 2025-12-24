<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Quartz Expense App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js (Needed for dropdown + bottom sheet) -->
    {{--
    <script src="https://unpkg.com/alpinejs" defer></script> --}}
</head>

<body class="bg-gray-100 min-h-screen safe-area-padding" x-data="{ more: false }">

    <!-- MOBILE HEADER -->
    <div
        class="md:hidden fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-indigo-900 to-purple-900 text-white pt-safe-top">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-2">
                <div
                    class="h-8 w-8 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-gem text-white text-sm"></i>
                </div>
                <h1 class="font-semibold text-lg">Quartz</h1>
                <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">Expense</span>
            </div>
            <button class="h-10 w-10 rounded-full bg-white/10 flex items-center justify-center">
                <i class="fas fa-bell text-white"></i>
            </button>
        </div>
        <div class="h-1 bg-gradient-to-r from-cyan-500 to-blue-500"></div>
    </div>

    <!-- MOBILE BOTTOM NAV -->
    <div
        class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-gray-200/50 pt-2 pb-safe-bottom shadow-lg">
        <div class="flex justify-around items-center px-2">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center space-y-1 p-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Dashboard</span>
            </a>

            <a href="{{ route('categories.index') }}" wire:navigate class="flex flex-col items-center space-y-1 p-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-tags text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Categories</span>
            </a>

            <a href="{{ route('brands.index') }}" wire:navigate class="flex flex-col items-center space-y-1 p-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                    <i class="fas fa-copyright text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Brands</span>
            </a>

            <!-- MORE BUTTON -->
            <button @click="more = true" class="flex flex-col items-center space-y-1 p-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                    <i class="fas fa-ellipsis-h text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">More</span>
            </button>
        </div>
    </div>

    <!-- MOBILE BOTTOM SHEET (More Menu) -->
    <div x-show="more" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50"
        @click.self="more = false">
        <div x-show="more" x-transition.duration.300ms
            class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 shadow-xl">

            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">More Options</h2>

            <div class="grid grid-cols-3 gap-4 text-center">
                <a href="{{ route('items.index') }}" wire:navigate class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-cube text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Items</p>
                </a>
                <a href="{{ route('shops.index') }}" class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-shop text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Shops</p>
                </a>
                <a href="{{ route('units.index') }}" wire:navigate class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-balance-scale text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Units</p>
                </a>
                <a href="{{ route('quartzs.index') }}" class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-home text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Quartzs</p>
                </a>
                <a href="#" class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-arrow-up text-xl mb-1"></i>
                    <p class="text-xs font-semibold">GDN</p>
                </a>
                <a href="#" class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Profile</p>
                </a>
                <a href="#" class="p-4 rounded-xl bg-gray-100 hover:bg-gray-200">
                    <i class="fas fa-cog text-xl mb-1"></i>
                    <p class="text-xs font-semibold">Settings</p>
                </a>


                <div class="col-span-3">

                    <livewire:layout.navigation />
                </div>
            </div>

            <button @click="more = false"
                class="mt-6 w-full bg-gray-800 text-white py-3 rounded-xl text-sm font-medium">
                Close
            </button>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="flex min-h-screen pt-16 md:pt-0"> <!-- added pt-16 to avoid mobile header overlap -->

        <!-- DESKTOP SIDEBAR -->
        <aside
            class="hidden md:flex w-72 bg-gradient-to-b from-indigo-900 via-purple-900 to-violet-900 shadow-2xl backdrop-blur-lg border-r border-white/10 px-6 py-8 flex flex-col">

            <!-- Logo -->
            <div class="mb-10 px-2">
                <div class="flex items-center space-x-3">
                    <div
                        class="h-10 w-10 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-gem text-white text-lg"></i>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent">
                            Quartz System</h1>
                        <p class="text-xs text-gray-300/70 mt-0.5">Expense Management</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="space-y-2 flex-1">
                <div class="px-2 mb-4">
                    <p class="text-xs font-semibold text-gray-400/70 uppercase tracking-wider">Main Menu</p>
                </div>
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium">Dashboard</span>
                </a>
                <a href="{{ route('categories.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-tags text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium">Categories</span>
                </a>
                <a href="{{ route('brands.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                        <i class="fas fa-copyright text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium">Brands</span>
                </a>

                <a href="{{ route('items.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center">
                        <i class="fas fa-cube text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium">Items</span>
                </a>

                <a href="{{ route('shops.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/10">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-yellow-500 to-teal-500 flex items-center justify-center">
                        <i class="fas fa-shop text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium">Shops</span>
                </a>


                <a href="{{ route('units.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:translate-x-1 group">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-balance-scale text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium group-hover:text-white">Units</span>
                </a>
                <a href="{{ route('quartzs.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:translate-x-1 group">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-red-500 to-yellow-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-home text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium group-hover:text-white">Quartzs</span>
                </a>

                <a href="{{ route('grns.index') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:translate-x-1 group">
                    <div
                        class="h-9 w-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-arrow-down text-white text-sm"></i>
                    </div>
                    <span class="text-gray-200 font-medium group-hover:text-white">GRN</span>
                </a>

                <div class="px-2 mt-10 mb-4">
                    <p class="text-xs font-semibold text-gray-400/70 uppercase tracking-wider">Coming Soon</p>
                </div>

                @foreach ([['icon' => 'fas fa-arrow-up', 'text' => 'GDN', 'gradient' => 'from-rose-500 to-pink-500'], ['icon' => 'fas fa-university', 'text' => 'Bank', 'gradient' => 'from-lime-500 to-green-500'], ['icon' => 'fas fa-users', 'text' => 'Users', 'gradient' => 'from-violet-500 to-purple-500']] as $item)
                    <div class="flex items-center opacity-50 space-x-3 p-3 rounded-xl cursor-not-allowed">
                        <div
                            class="h-9 w-9 rounded-lg bg-gradient-to-br {{ $item['gradient'] }} flex items-center justify-center">
                            <i class="{{ $item['icon'] }} text-white text-sm"></i>
                        </div>
                        <span class="text-gray-400/70 font-medium">{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </nav>

            <!-- PROFILE DROPDOWN -->
            <div x-data="{ open: false }" class="mt-auto pt-6 border-t border-white/10">
                <div @click="open = !open"
                    class="flex items-center space-x-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 cursor-pointer">
                    <div
                        class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">Administrator</p>
                        <p class="text-xs text-gray-400/80">admin@quartz.com</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                </div>
                <div x-show="open" x-transition class="mt-2 bg-white/10 p-3 rounded-xl text-gray-200 space-y-2">
                    <a href="#" class="block hover:text-white">Profile</a>
                    <a href="#" class="block hover:text-white">Settings</a>
                    <livewire:layout.navigation />
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto pt-safe-top pb-safe-bottom">
            <div class="min-h-screen md:min-h-0">

                <!-- DESKTOP HEADER -->
                <div class="hidden md:block p-8">
                    <h2 class="text-3xl font-bold text-gray-800">Welcome back</h2>
                    <p class="text-gray-600 mt-1">Manage your expenses efficiently</p>
                </div>

                <!-- CONTENT -->
                <div class="md:px-8">
                    <div
                        class="md:bg-white/80 md:backdrop-blur-sm md:rounded-2xl md:border md:border-white/50 md:shadow-xl md:overflow-hidden">
                        {{ $slot }}
                    </div>
                </div>

                <div class="h-20 md:h-0"></div>
            </div>
        </main>
    </div>

    @livewireScripts

    <style>
        .safe-area-padding {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }

        .pt-safe-top {
            padding-top: max(env(safe-area-inset-top), 0.75rem);
        }

        .pb-safe-bottom {
            padding-bottom: max(env(safe-area-inset-bottom), 0.75rem);
        }
    </style>

</body>

</html>