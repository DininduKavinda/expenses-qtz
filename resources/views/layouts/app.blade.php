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
    }
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

    <!-- MAIN LAYOUT -->
    <div class="flex min-h-screen pt-16 md:pt-0">

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
            <nav class="space-y-1 flex-1 overflow-y-auto">
                <!-- Quick Access -->
                <div class="px-2 mb-2">
                    <p class="text-xs font-semibold text-gray-400/70 uppercase tracking-wider">Quick Access</p>
                </div>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="flex items-center space-x-3 p-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                    <div
                        class="h-9 w-9 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-blue-600 to-cyan-500 ring-2 ring-cyan-300' : 'bg-gradient-to-br from-blue-500 to-cyan-400' }} flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-chart-pie text-white text-sm"></i>
                    </div>
                    <span
                        class="font-medium {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Dashboard</span>
                </a>

                <!-- Reports -->
                @can('view-any-reports')
                    <a href="{{ route('reports') }}" wire:navigate
                        class="flex items-center space-x-3 p-3 rounded-xl {{ request()->routeIs('reports') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                        <div
                            class="h-9 w-9 rounded-lg {{ request()->routeIs('reports') ? 'bg-gradient-to-br from-indigo-600 to-blue-700 ring-2 ring-blue-300' : 'bg-gradient-to-br from-indigo-500 to-blue-600' }} flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        <span
                            class="font-medium {{ request()->routeIs('reports') ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Reports</span>
                    </a>
                @endcan

                <!-- Metadata Dropdown -->
                @php
                    $isMetadataActive = request()->routeIs('categories.*') ||
                        request()->routeIs('brands.*') ||
                        request()->routeIs('shops.*') ||
                        request()->routeIs('units.*') ||
                        request()->routeIs('quartzs.*');
                @endphp
                <div x-data="{ open: {{ $isMetadataActive ? 'true' : 'false' }} }" class="mt-4">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-xl {{ $isMetadataActive ? 'bg-white/20' : 'hover:bg-white/10' }} group">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-9 w-9 rounded-lg {{ $isMetadataActive ? 'bg-gradient-to-br from-purple-600 to-pink-600 ring-2 ring-pink-300' : 'bg-gradient-to-br from-purple-500 to-pink-500' }} flex items-center justify-center shadow-md">
                                <i class="fas fa-database text-white text-sm"></i>
                            </div>
                            <span
                                class="font-medium {{ $isMetadataActive ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Metadata</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform"
                            :class="{ 'rotate-180': open, 'text-white': {{ $isMetadataActive ? 'true' : 'false' }} }"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-white/10 space-y-1 mt-1">
                        @can('view-any-categories')
                            <a href="{{ route('categories.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-purple-500/40' : 'bg-purple-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-tag {{ request()->routeIs('categories.*') ? 'text-purple-200' : 'text-purple-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('categories.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Categories</span>
                            </a>
                        @endcan

                        @can('view-any-brands')
                            <a href="{{ route('brands.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('brands.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('brands.*') ? 'bg-orange-500/40' : 'bg-orange-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-copyright {{ request()->routeIs('brands.*') ? 'text-orange-200' : 'text-orange-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('brands.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Brands</span>
                            </a>
                        @endcan

                        @can('view-any-shops')
                            <a href="{{ route('shops.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('shops.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('shops.*') ? 'bg-yellow-500/40' : 'bg-yellow-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-shop {{ request()->routeIs('shops.*') ? 'text-yellow-200' : 'text-yellow-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('shops.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Shops</span>
                            </a>
                        @endcan

                        @can('view-any-units')
                            <a href="{{ route('units.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('units.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('units.*') ? 'bg-cyan-500/40' : 'bg-cyan-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-balance-scale {{ request()->routeIs('units.*') ? 'text-cyan-200' : 'text-cyan-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('units.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Units</span>
                            </a>
                        @endcan

                        @can('view-any-quartzs')
                            <a href="{{ route('quartzs.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('quartzs.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('quartzs.*') ? 'bg-red-500/40' : 'bg-red-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-home {{ request()->routeIs('quartzs.*') ? 'text-red-200' : 'text-red-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('quartzs.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Quartzs</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Goods Management Dropdown -->
                @php
                    $isGoodsActive = request()->routeIs('items.*') ||
                        request()->routeIs('grns.*') ||
                        request()->routeIs('gdns.*');
                @endphp
                <div x-data="{ open: {{ $isGoodsActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-xl {{ $isGoodsActive ? 'bg-white/20' : 'hover:bg-white/10' }} group">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-9 w-9 rounded-lg {{ $isGoodsActive ? 'bg-gradient-to-br from-emerald-600 to-teal-600 ring-2 ring-teal-300' : 'bg-gradient-to-br from-emerald-500 to-teal-500' }} flex items-center justify-center shadow-md">
                                <i class="fas fa-boxes text-white text-sm"></i>
                            </div>
                            <span
                                class="font-medium {{ $isGoodsActive ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Goods
                                Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform"
                            :class="{ 'rotate-180': open, 'text-white': {{ $isGoodsActive ? 'true' : 'false' }} }"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-white/10 space-y-1 mt-1">
                        @can('view-any-items')
                            <a href="{{ route('items.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('items.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('items.*') ? 'bg-emerald-500/40' : 'bg-emerald-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-cube {{ request()->routeIs('items.*') ? 'text-emerald-200' : 'text-emerald-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('items.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Items</span>
                            </a>
                        @endcan

                        @can('view-any-grns')
                            <a href="{{ route('grns.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('grns.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('grns.*') ? 'bg-amber-500/40' : 'bg-amber-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-arrow-down {{ request()->routeIs('grns.*') ? 'text-amber-200' : 'text-amber-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('grns.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">GRN</span>
                            </a>
                        @endcan

                        @can('view-any-gdns')
                            <a href="{{ route('gdns.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('gdns.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('gdns.*') ? 'bg-rose-500/40' : 'bg-rose-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-arrow-up {{ request()->routeIs('gdns.*') ? 'text-rose-200' : 'text-rose-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('gdns.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">GDN</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Financial Dropdown -->
                @php
                    $isFinancialActive = request()->routeIs('banks.*') ||
                        request()->routeIs('my-expenses');
                @endphp
                <div x-data="{ open: {{ $isFinancialActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-xl {{ $isFinancialActive ? 'bg-white/20' : 'hover:bg-white/10' }} group">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-9 w-9 rounded-lg {{ $isFinancialActive ? 'bg-gradient-to-br from-green-600 to-emerald-600 ring-2 ring-emerald-300' : 'bg-gradient-to-br from-green-500 to-emerald-500' }} flex items-center justify-center shadow-md">
                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                            </div>
                            <span
                                class="font-medium {{ $isFinancialActive ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Financial</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform"
                            :class="{ 'rotate-180': open, 'text-white': {{ $isFinancialActive ? 'true' : 'false' }} }"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-white/10 space-y-1 mt-1">
                        @can('view-any-banks')
                            <a href="{{ route('banks.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('banks.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('banks.*') ? 'bg-green-500/40' : 'bg-green-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-university {{ request()->routeIs('banks.*') ? 'text-green-200' : 'text-green-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('banks.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Bank
                                    Accounts</span>
                            </a>
                        @endcan

                        @can('view-any-expense-splits')
                            <a href="{{ route('my-expenses') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('my-expenses') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('my-expenses') ? 'bg-rose-500/40' : 'bg-rose-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-receipt {{ request()->routeIs('my-expenses') ? 'text-rose-200' : 'text-rose-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('my-expenses') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">My
                                    Expenses</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Administration Dropdown -->
                @php
                    $isAdminActive = request()->routeIs('users.*') ||
                        request()->routeIs('roles.*');
                @endphp
                <div x-data="{ open: {{ $isAdminActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-xl {{ $isAdminActive ? 'bg-white/20' : 'hover:bg-white/10' }} group">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-9 w-9 rounded-lg {{ $isAdminActive ? 'bg-gradient-to-br from-violet-600 to-purple-600 ring-2 ring-purple-300' : 'bg-gradient-to-br from-violet-500 to-purple-500' }} flex items-center justify-center shadow-md">
                                <i class="fas fa-user-cog text-white text-sm"></i>
                            </div>
                            <span
                                class="font-medium {{ $isAdminActive ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Administration</span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform"
                            :class="{ 'rotate-180': open, 'text-white': {{ $isAdminActive ? 'true' : 'false' }} }"></i>
                    </button>

                    <div x-show="open" x-collapse class="ml-4 pl-4 border-l border-white/10 space-y-1 mt-1">
                        @can('view-any-users')
                            <a href="{{ route('users.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('users.*') ? 'bg-violet-500/40' : 'bg-violet-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-users {{ request()->routeIs('users.*') ? 'text-violet-200' : 'text-violet-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Users</span>
                            </a>
                        @endcan

                        @can('view-any-roles')
                            <a href="{{ route('roles.index') }}" wire:navigate
                                class="flex items-center space-x-3 p-2 rounded-lg {{ request()->routeIs('roles.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                                <div
                                    class="h-7 w-7 rounded-lg {{ request()->routeIs('roles.*') ? 'bg-indigo-500/40' : 'bg-indigo-500/20' }} flex items-center justify-center">
                                    <i
                                        class="fas fa-user-shield {{ request()->routeIs('roles.*') ? 'text-indigo-200' : 'text-indigo-300' }} text-xs"></i>
                                </div>
                                <span
                                    class="text-sm {{ request()->routeIs('roles.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}">Roles</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Settings -->
                @can('manage-settings')
                    <div class="mt-4">
                        <a href="{{ route('settings') }}" wire:navigate
                            class="flex items-center space-x-3 p-3 rounded-xl {{ request()->routeIs('settings') ? 'bg-white/20 text-white' : 'hover:bg-white/10 group' }}">
                            <div
                                class="h-9 w-9 rounded-lg {{ request()->routeIs('settings') ? 'bg-gradient-to-br from-gray-700 to-slate-800 ring-2 ring-slate-300' : 'bg-gradient-to-br from-gray-600 to-slate-700' }} flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                <i class="fas fa-cog text-white text-sm"></i>
                            </div>
                            <span
                                class="font-medium {{ request()->routeIs('settings') ? 'text-white' : 'text-gray-200 group-hover:text-white' }}">Settings</span>
                        </a>
                    </div>
                @endcan
            </nav>

            <!-- PROFILE DROPDOWN -->
            <div x-data="{ open: false }" class="mt-auto pt-6 border-t border-white/10">
                <div @click="open = !open"
                    class="flex items-center space-x-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 cursor-pointer group">
                    <div
                        class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-gray-400/80">{{ auth()->user()->email ?? 'admin@quartz.com' }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-sm transition-transform"
                        :class="{ 'rotate-180': open }"></i>
                </div>
                <div x-show="open" x-transition class="mt-2 bg-white/10 p-3 rounded-xl text-gray-200 space-y-2">
                    <a href="#" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white/10">
                        <i class="fas fa-user-circle text-sm"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-white/10">
                        <i class="fas fa-cog text-sm"></i>
                        <span>Settings</span>
                    </a>
                    <div class="border-t border-white/10 pt-2">
                        <livewire:layout.navigation />
                    </div>
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

        /* Custom scrollbar for sidebar */
        aside nav::-webkit-scrollbar {
            width: 4px;
        }

        aside nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        aside nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        aside nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Active state animations */
        .active-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
    </style>

</body>

</html>