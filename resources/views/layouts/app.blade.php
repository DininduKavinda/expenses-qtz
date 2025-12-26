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

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

</head>

<body class="bg-gray-100 min-h-screen safe-area-padding" x-data="{ 
    more: false,
    desktopDropdowns: {
        metadata: false,
        goods: false,
        management: false
    },
    sidebarOpen: true
}" @route-updated.window="handleRouteChange">

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
            <a href="{{ route('dashboard') }}" wire:navigate
                class="flex flex-col items-center space-y-1 p-2 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">
                <div
                    class="h-10 w-10 rounded-full {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-600 to-cyan-500 ring-2 ring-blue-300' : 'bg-gradient-to-br from-blue-500 to-cyan-400' }} flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-sm"></i>
                </div>
                <span
                    class="text-xs font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-700' }}">Dashboard</span>
            </a>

            <button @click="more = true" class="flex flex-col items-center space-y-1 p-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-th-large text-white text-sm"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Modules</span>
            </button>

            @can('view-any-items')
                <a href="{{ route('items.index') }}" wire:navigate
                    class="flex flex-col items-center space-y-1 p-2 {{ request()->routeIs('items.*') ? 'text-emerald-600' : '' }}">
                    <div
                        class="h-10 w-10 rounded-full {{ request()->routeIs('items.*') ? 'bg-gradient-to-br from-emerald-600 to-teal-500 ring-2 ring-emerald-300' : 'bg-gradient-to-br from-emerald-500 to-teal-500' }} flex items-center justify-center">
                        <i class="fas fa-cube text-white text-sm"></i>
                    </div>
                    <span
                        class="text-xs font-medium {{ request()->routeIs('items.*') ? 'text-emerald-600' : 'text-gray-700' }}">Items</span>
                </a>
            @endcan

            @can('view-any-grns')
                <a href="{{ route('grns.index') }}" wire:navigate
                    class="flex flex-col items-center space-y-1 p-2 {{ request()->routeIs('grns.*') ? 'text-amber-600' : '' }}">
                    <div
                        class="h-10 w-10 rounded-full {{ request()->routeIs('grns.*') ? 'bg-gradient-to-br from-amber-600 to-orange-500 ring-2 ring-amber-300' : 'bg-gradient-to-br from-amber-500 to-orange-500' }} flex items-center justify-center">
                        <i class="fas fa-arrow-down text-white text-sm"></i>
                    </div>
                    <span
                        class="text-xs font-medium {{ request()->routeIs('grns.*') ? 'text-amber-600' : 'text-gray-700' }}">GRN</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- MOBILE BOTTOM SHEET (All Modules) -->
    <div x-show="more" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50"
        @click.self="more = false">
        <div x-show="more" x-transition.duration.300ms
            class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">All Modules</h2>
                <button @click="more = false" class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-times text-gray-600"></i>
                </button>
            </div>

            <!-- Mobile Grid Layout -->
            <div class="grid grid-cols-4 gap-3">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-100 to-cyan-100 border-2 border-blue-300' : 'bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100 hover:border-blue-300' }} hover:shadow-md transition-all">
                    <div
                        class="h-12 w-12 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-600 to-cyan-500 ring-2 ring-blue-300' : 'bg-gradient-to-br from-blue-500 to-cyan-400' }} flex items-center justify-center mb-2">
                        <i class="fas fa-chart-pie text-white text-lg"></i>
                    </div>
                    <span
                        class="text-xs font-semibold {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-700' }} text-center">Dashboard</span>
                </a>

                <!-- Reports -->
                @can('view-any-reports')
                    <a href="{{ route('reports') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('reports') ? 'bg-gradient-to-br from-indigo-100 to-blue-100 border-2 border-indigo-300' : 'bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 hover:border-indigo-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('reports') ? 'bg-gradient-to-br from-indigo-600 to-blue-700 ring-2 ring-indigo-300' : 'bg-gradient-to-br from-indigo-600 to-blue-700' }} flex items-center justify-center mb-2 shadow-lg">
                            <i class="fas fa-chart-bar text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('reports') ? 'text-indigo-700' : 'text-gray-700' }} text-center">Reports</span>
                    </a>
                @endcan

                <!-- Metadata Group -->
                <div class="col-span-4 mt-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">Metadata</h3>
                </div>

                @can('view-any-categories')
                    <a href="{{ route('categories.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('categories.*') ? 'bg-gradient-to-br from-purple-100 to-pink-100 border-2 border-purple-300' : 'bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100 hover:border-purple-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-gradient-to-br from-purple-600 to-pink-600 ring-2 ring-purple-300' : 'bg-gradient-to-br from-purple-500 to-pink-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-tags text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('categories.*') ? 'text-purple-700' : 'text-gray-700' }} text-center">Categories</span>
                    </a>
                @endcan

                @can('view-any-brands')
                    <a href="{{ route('brands.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('brands.*') ? 'bg-gradient-to-br from-orange-100 to-red-100 border-2 border-orange-300' : 'bg-gradient-to-br from-orange-50 to-red-50 border border-orange-100 hover:border-orange-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('brands.*') ? 'bg-gradient-to-br from-orange-600 to-red-600 ring-2 ring-orange-300' : 'bg-gradient-to-br from-orange-500 to-red-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-copyright text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('brands.*') ? 'text-orange-700' : 'text-gray-700' }} text-center">Brands</span>
                    </a>
                @endcan

                @can('view-any-shops')
                    <a href="{{ route('shops.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('shops.*') ? 'bg-gradient-to-br from-yellow-100 to-green-100 border-2 border-yellow-300' : 'bg-gradient-to-br from-yellow-50 to-green-50 border border-yellow-100 hover:border-yellow-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('shops.*') ? 'bg-gradient-to-br from-yellow-600 to-green-600 ring-2 ring-yellow-300' : 'bg-gradient-to-br from-yellow-500 to-green-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-shop text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('shops.*') ? 'text-yellow-700' : 'text-gray-700' }} text-center">Shops</span>
                    </a>
                @endcan

                @can('view-any-units')
                    <a href="{{ route('units.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('units.*') ? 'bg-gradient-to-br from-blue-100 to-cyan-100 border-2 border-blue-300' : 'bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100 hover:border-blue-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('units.*') ? 'bg-gradient-to-br from-blue-600 to-cyan-600 ring-2 ring-blue-300' : 'bg-gradient-to-br from-blue-500 to-cyan-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-balance-scale text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('units.*') ? 'text-blue-700' : 'text-gray-700' }} text-center">Units</span>
                    </a>
                @endcan

                @can('view-any-quartzs')
                    <a href="{{ route('quartzs.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('quartzs.*') ? 'bg-gradient-to-br from-red-100 to-yellow-100 border-2 border-red-300' : 'bg-gradient-to-br from-red-50 to-yellow-50 border border-red-100 hover:border-red-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('quartzs.*') ? 'bg-gradient-to-br from-red-600 to-yellow-600 ring-2 ring-red-300' : 'bg-gradient-to-br from-red-500 to-yellow-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-home text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('quartzs.*') ? 'text-red-700' : 'text-gray-700' }} text-center">Quartzs</span>
                    </a>
                @endcan

                <!-- Goods Management Group -->
                <div class="col-span-4 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">Goods Management
                    </h3>
                </div>

                @can('view-any-items')
                    <a href="{{ route('items.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('items.*') ? 'bg-gradient-to-br from-emerald-100 to-teal-100 border-2 border-emerald-300' : 'bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 hover:border-emerald-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('items.*') ? 'bg-gradient-to-br from-emerald-600 to-teal-600 ring-2 ring-emerald-300' : 'bg-gradient-to-br from-emerald-500 to-teal-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-cube text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('items.*') ? 'text-emerald-700' : 'text-gray-700' }} text-center">Items</span>
                    </a>
                @endcan

                @can('view-any-grns')
                    <a href="{{ route('grns.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('grns.*') ? 'bg-gradient-to-br from-amber-100 to-orange-100 border-2 border-amber-300' : 'bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100 hover:border-amber-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('grns.*') ? 'bg-gradient-to-br from-amber-600 to-orange-600 ring-2 ring-amber-300' : 'bg-gradient-to-br from-amber-500 to-orange-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-arrow-down text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('grns.*') ? 'text-amber-700' : 'text-gray-700' }} text-center">GRN</span>
                    </a>
                @endcan

                @can('view-any-gdns')
                    <a href="{{ route('gdns.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('gdns.*') ? 'bg-gradient-to-br from-rose-100 to-pink-100 border-2 border-rose-300' : 'bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-100 hover:border-rose-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('gdns.*') ? 'bg-gradient-to-br from-rose-600 to-pink-600 ring-2 ring-rose-300' : 'bg-gradient-to-br from-rose-500 to-pink-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-arrow-up text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('gdns.*') ? 'text-rose-700' : 'text-gray-700' }} text-center">GDN</span>
                    </a>
                @endcan

                <!-- Financial Group -->
                <div class="col-span-4 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">Financial</h3>
                </div>

                @can('view-any-banks')
                    <a href="{{ route('banks.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('banks.*') ? 'bg-gradient-to-br from-green-100 to-emerald-100 border-2 border-green-300' : 'bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 hover:border-green-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('banks.*') ? 'bg-gradient-to-br from-green-600 to-emerald-600 ring-2 ring-green-300' : 'bg-gradient-to-br from-green-500 to-emerald-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-university text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('banks.*') ? 'text-green-700' : 'text-gray-700' }} text-center">Banks</span>
                    </a>
                @endcan

                @can('view-any-expense-splits')
                    <a href="{{ route('my-expenses') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('my-expenses') ? 'bg-gradient-to-br from-rose-100 to-pink-100 border-2 border-rose-300' : 'bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-100 hover:border-rose-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('my-expenses') ? 'bg-gradient-to-br from-rose-600 to-pink-600 ring-2 ring-rose-300' : 'bg-gradient-to-br from-rose-500 to-pink-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-receipt text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('my-expenses') ? 'text-rose-700' : 'text-gray-700' }} text-center">My
                            Expenses</span>
                    </a>
                @endcan

                <!-- Administration Group -->
                <div class="col-span-4 mt-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 px-1">Administration
                    </h3>
                </div>

                @can('view-any-users')
                    <a href="{{ route('users.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('users.*') ? 'bg-gradient-to-br from-violet-100 to-purple-100 border-2 border-violet-300' : 'bg-gradient-to-br from-violet-50 to-purple-50 border border-violet-100 hover:border-violet-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('users.*') ? 'bg-gradient-to-br from-violet-600 to-purple-600 ring-2 ring-violet-300' : 'bg-gradient-to-br from-violet-500 to-purple-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('users.*') ? 'text-violet-700' : 'text-gray-700' }} text-center">Users</span>
                    </a>
                @endcan

                @can('view-any-roles')
                    <a href="{{ route('roles.index') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('roles.*') ? 'bg-gradient-to-br from-indigo-100 to-blue-100 border-2 border-indigo-300' : 'bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 hover:border-indigo-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('roles.*') ? 'bg-gradient-to-br from-indigo-600 to-blue-600 ring-2 ring-indigo-300' : 'bg-gradient-to-br from-indigo-500 to-blue-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-user-shield text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('roles.*') ? 'text-indigo-700' : 'text-gray-700' }} text-center">Roles</span>
                    </a>
                @endcan

                @can('manage-settings')
                    <a href="{{ route('settings') }}" wire:navigate
                        class="flex flex-col items-center p-3 rounded-xl {{ request()->routeIs('settings') ? 'bg-gradient-to-br from-slate-100 to-gray-100 border-2 border-slate-300' : 'bg-gradient-to-br from-slate-50 to-gray-50 border border-slate-100 hover:border-slate-300' }} hover:shadow-md">
                        <div
                            class="h-12 w-12 rounded-lg {{ request()->routeIs('settings') ? 'bg-gradient-to-br from-slate-600 to-gray-600 ring-2 ring-slate-300' : 'bg-gradient-to-br from-slate-500 to-gray-500' }} flex items-center justify-center mb-2">
                            <i class="fas fa-cog text-white text-lg"></i>
                        </div>
                        <span
                            class="text-xs font-semibold {{ request()->routeIs('settings') ? 'text-slate-700' : 'text-gray-700' }} text-center">Settings</span>
                    </a>
                @endcan

                <!-- Logout -->
                <div class="col-span-4 mt-2">
                    <div class="p-3">
                        <livewire:layout.navigation />
                    </div>
                </div>
            </div>

            <button @click="more = false"
                class="mt-6 w-full bg-gradient-to-r from-gray-800 to-gray-900 text-white py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-shadow">
                Close Menu
            </button>
        </div>
    </div>

    <!-- DESKTOP LAYOUT -->
    <div class="hidden md:flex min-h-screen">
        <!-- FIXED SIDEBAR -->
        <aside :class="sidebarOpen ? 'w-72' : 'w-20'"
            class="fixed left-0 top-0 bottom-0 bg-gradient-to-b from-indigo-900 to-purple-900 text-white transition-all duration-300 z-40">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3" :class="!sidebarOpen && 'justify-center'">
                    <div
                        class="h-12 w-12 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-lg shrink-0">
                        <i class="fas fa-gem text-white text-lg"></i>
                    </div>
                    <div x-show="sidebarOpen" x-transition>
                        <h1 class="text-xl font-bold text-white">Quartz System</h1>
                        <p class="text-xs text-gray-300/70">Expense Management</p>
                    </div>
                </div>
            </div>

            <!-- Toggle Button -->
            <div class="p-4 border-b border-white/10 flex justify-end">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="h-8 w-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20">
                    <i class="fas fa-chevron-left text-white text-sm transition-transform"
                        :class="!sidebarOpen && 'rotate-180'"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 overflow-y-auto h-[calc(100vh-200px)]">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-lg mb-2 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                    <div
                        class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shrink-0">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="font-medium">Dashboard</span>
                </a>

                <!-- Reports -->
                @can('view-any-reports')
                    <a href="{{ route('reports') }}" wire:navigate
                        class="flex items-center space-x-3 p-3 rounded-lg mb-2 {{ request()->routeIs('reports') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                        <div
                            class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-600 to-blue-700 flex items-center justify-center shrink-0">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen" x-transition class="font-medium">Reports</span>
                    </a>
                @endcan

                <!-- Metadata Group -->
                <div class="mb-4">
                    <div x-show="sidebarOpen" x-transition
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Metadata</div>
                    <div class="space-y-1">
                        @can('view-any-categories')
                            <a href="{{ route('categories.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-tags text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Categories</span>
                            </a>
                        @endcan

                        @can('view-any-brands')
                            <a href="{{ route('brands.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('brands.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-copyright text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Brands</span>
                            </a>
                        @endcan

                        @can('view-any-shops')
                            <a href="{{ route('shops.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('shops.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-yellow-500 to-green-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-shop text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Shops</span>
                            </a>
                        @endcan

                        @can('view-any-units')
                            <a href="{{ route('units.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('units.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-balance-scale text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Units</span>
                            </a>
                        @endcan

                        @can('view-any-quartzs')
                            <a href="{{ route('quartzs.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('quartzs.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-red-500 to-yellow-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-home text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Quartzs</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Goods Management Group -->
                <div class="mb-4">
                    <div x-show="sidebarOpen" x-transition
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Goods Management
                    </div>
                    <div class="space-y-1">
                        @can('view-any-items')
                            <a href="{{ route('items.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('items.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-cube text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Items</span>
                            </a>
                        @endcan

                        @can('view-any-grns')
                            <a href="{{ route('grns.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('grns.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-arrow-down text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>GRN</span>
                            </a>
                        @endcan

                        @can('view-any-gdns')
                            <a href="{{ route('gdns.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('gdns.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-arrow-up text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>GDN</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Financial Group -->
                <div class="mb-4">
                    <div x-show="sidebarOpen" x-transition
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Financial</div>
                    <div class="space-y-1">
                        @can('view-any-banks')
                            <a href="{{ route('banks.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('banks.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-university text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Banks</span>
                            </a>
                        @endcan

                        @can('view-any-expense-splits')
                            <a href="{{ route('my-expenses') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('my-expenses') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-receipt text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>My Expenses</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Administration Group -->
                <div>
                    <div x-show="sidebarOpen" x-transition
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Administration
                    </div>
                    <div class="space-y-1">
                        @can('view-any-users')
                            <a href="{{ route('users.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('users.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Users</span>
                            </a>
                        @endcan

                        @can('view-any-roles')
                            <a href="{{ route('roles.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('roles.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-user-shield text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Roles</span>
                            </a>
                        @endcan

                        @can('manage-settings')
                            <a href="{{ route('settings') }}" wire:navigate
                                class="flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('settings') ? 'bg-white/20 text-white' : 'hover:bg-white/10 text-gray-200 hover:text-white' }}">
                                <div
                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-slate-500 to-gray-500 flex items-center justify-center shrink-0">
                                    <i class="fas fa-cog text-white text-sm"></i>
                                </div>
                                <span x-show="sidebarOpen" x-transition>Settings</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 ml-0 transition-all duration-300" :class="sidebarOpen ? 'ml-72' : 'ml-20'">
            <!-- TOP BAR -->
            <div class="fixed top-0 right-0 left-0 z-30 bg-white border-b border-gray-200 shadow-sm"
                :class="sidebarOpen ? 'left-72' : 'left-20'">
                <div class="flex items-center justify-between px-6 py-3">
                    <!-- Breadcrumb/Page Title -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            @yield('title', 'Dashboard')
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            @yield('subtitle', 'Welcome to Quartz Expense Management System')
                        </p>
                    </div>

                    <!-- Right Side: Notifications, User Profile, Logout -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 relative">
                                <i class="fas fa-bell text-gray-600"></i>
                                <span
                                    class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                            <!-- Notification Dropdown -->
                            <div x-show="open" x-transition
                                class="absolute top-full right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <h3 class="font-medium text-gray-800">Notifications</h3>
                                    <p class="text-xs text-gray-500">You have 3 unread notifications</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <!-- Notification Items -->
                                    <a href="#"
                                        class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-chart-line text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">Monthly Report Ready</p>
                                            <p class="text-xs text-gray-500">5 minutes ago</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div
                                            class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">GRN Approved</p>
                                            <p class="text-xs text-gray-500">2 hours ago</p>
                                        </div>
                                    </a>
                                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50">
                                        <div
                                            class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-amber-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">Low Stock Alert</p>
                                            <p class="text-xs text-gray-500">Yesterday</p>
                                        </div>
                                    </a>
                                </div>
                                <a href="#" class="block text-center py-2 text-sm text-blue-600 hover:bg-gray-50">
                                    View All Notifications
                                </a>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                <div
                                    class="h-9 w-9 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'Admin' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ auth()->user()->role?->name ?? 'Administrator' }}
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"
                                    :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name ?? 'Admin' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@quartz.com' }}
                                    </p>
                                </div>
                                <a href="#" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-user-circle text-gray-600"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="#" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-cog text-gray-600"></i>
                                    <span>Account Settings</span>
                                </a>
                                <a href="#" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-shield-alt text-gray-600"></i>
                                    <span>Privacy & Security</span>
                                </a>
                                <div class="border-t border-gray-100 pt-2">
                                    <livewire:layout.navigation />

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENT AREA -->
            <div class="pt-16 min-h-screen">
                <div class="p-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MOBILE CONTENT AREA -->
    <main class="md:hidden min-h-screen pt-safe-top pb-safe-bottom">
        <div class="px-4 py-6">
            <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-gray-200 shadow-lg overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </main>

    @livewireScripts

    <script>
        // Alpine.js for dynamic behavior
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                init() {
                    // Auto-open dropdowns that contain active items
                    this.checkActiveDropdowns();
                },

                checkActiveDropdowns() {
                    // This method will be called on route changes
                    const activePaths = {
                        metadata: ['categories', 'brands', 'shops', 'units', 'quartzs'],
                        goods: ['items', 'grns', 'gdns'],
                        financial: ['banks', 'my-expenses'],
                        admin: ['users', 'roles']
                    };

                    const currentPath = window.location.pathname;

                    // Check which dropdown should be open
                    Object.entries(activePaths).forEach(([dropdown, paths]) => {
                        const isActive = paths.some(path => currentPath.includes(path));
                        if (isActive) {
                            this.$refs[`${dropdown}Dropdown`]?.setAttribute('x-data', '{ open: true }');
                        }
                    });
                }
            }));
        });

        // Handle route changes to update active states
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                const dropdowns = document.querySelectorAll('[x-data*="open"]');
                dropdowns.forEach(dropdown => {
                    const dropdownName = dropdown.querySelector('button')?.textContent?.trim().toLowerCase();
                    const currentPath = window.location.pathname;

                    const pathMapping = {
                        'metadata': ['categories', 'brands', 'shops', 'units', 'quartzs'],
                        'goods': ['items', 'grns', 'gdns'],
                        'financial': ['banks', 'my-expenses'],
                        'admin': ['users', 'roles', 'settings']
                    };

                    if (dropdownName && pathMapping[dropdownName]) {
                        const isActive = pathMapping[dropdownName].some(path => currentPath.includes(path));
                        if (isActive) {
                            dropdown.setAttribute('x-data', '{ open: true }');
                        }
                    }
                });
            }, 100);
        });
    </script>

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

        /* Smooth transitions */
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        /* Sidebar scrollbar */
        nav::-webkit-scrollbar {
            width: 4px;
        }

        nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Active state animations */
        .active-nav-item {
            position: relative;
        }

        .active-nav-item::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, #22d3ee, #3b82f6);
            border-radius: 2px;
        }

        /* Top bar fixed positioning */
        .fixed {
            position: fixed;
        }
    </style>

</body>

</html>