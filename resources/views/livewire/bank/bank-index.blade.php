<div>
    <!-- Header -->
    <div class="py-4">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Header -->
            <div class="md:hidden space-y-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-lg text-gray-800">
                            {{ auth()->user()->role?->slug === 'admin' ? 'Global Bank Audit' : 'Bank Accounts' }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Manage your bank accounts</p>
                    </div>
                    @can('create-banks')
                        <button wire:click="$set('showCreateModal', true)"
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium flex items-center gap-1">
                            <i class="fas fa-plus"></i>
                            <span>New</span>
                        </button>
                    @endcan
                </div>

                <!-- Mobile Quick Stats -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-red-50 p-3 rounded-lg border border-red-100">
                        <p class="text-xs font-bold text-red-600 uppercase mb-1">Total Debt</p>
                        <div class="flex items-baseline">
                            <span class="text-xl font-bold text-red-700">{{ number_format($userDebt, 2) }}</span>
                            <span class="text-xs text-red-500 ml-1">LKR</span>
                        </div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                        <p class="text-xs font-bold text-green-600 uppercase mb-1">Available Credit</p>
                        <div class="flex items-baseline">
                            <span class="text-xl font-bold text-green-700">{{ number_format($userCredit, 2) }}</span>
                            <span class="text-xs text-green-500 ml-1">LKR</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ auth()->user()->role?->slug === 'admin' ? __('Global Bank Audit') : __('Bank Accounts') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Review and manage your financial accounts</p>
                </div>
                @can('create-banks')
                    <button wire:click="$set('showCreateModal', true)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Create Account</span>
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Desktop Financial Status Card -->
            <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 mb-6">
                <div class="px-6 py-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Your Financial Status</h2>
                            <p class="text-sm text-gray-500 mt-1">Review your total expenses and available credits</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Gross Debt -->
                            <div class="bg-red-50 px-6 py-4 rounded-xl border border-red-100 min-w-[180px]">
                                <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Total
                                    Pending Debt</p>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-2xl font-black text-red-700">{{ number_format($userDebt, 2) }}</span>
                                    <span class="text-xs font-bold text-red-500 uppercase">LKR</span>
                                </div>
                            </div>
                            <!-- Available Credit -->
                            <div class="bg-green-50 px-6 py-4 rounded-xl border border-green-100 min-w-[180px]">
                                <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-1">Available
                                    Credit</p>
                                <div class="flex items-baseline gap-1">
                                    <span
                                        class="text-2xl font-black text-green-700">{{ number_format($userCredit, 2) }}</span>
                                    <span class="text-xs font-bold text-green-500 uppercase">LKR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($accounts->isNotEmpty())
                <!-- Mobile Bank Accounts Cards -->
                <div class="md:hidden space-y-4">
                    @foreach($accounts as $account)
                        @can('view-banks')
                            <a href="{{ route('banks.show', $account) }}" class="block">
                                <div
                                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <!-- Card Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                            <p class="text-xs text-gray-500 mt-1">Bank Account</p>
                                        </div>
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-university text-blue-600 text-sm"></i>
                                        </div>
                                    </div>

                                    <!-- Balance -->
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Current Balance</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            LKR {{ number_format($account->balance, 2) }}
                                        </p>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                        <span class="text-xs text-blue-600 font-medium">Tap to manage →</span>
                                        <span class="text-xs text-gray-500">
                                            {{ $account->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-base font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">Bank Account</p>
                                    </div>
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-university text-blue-600 text-sm"></i>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Current Balance</p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        LKR {{ number_format($account->balance, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endcan
                    @endforeach
                </div>

                <!-- Desktop Bank Accounts Grid -->
                <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($accounts as $account)
                        @can('view-banks')
                            <a href="{{ route('banks.show', $account) }}" class="block">
                                <div
                                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition duration-150 ease-in-out h-full border border-gray-100">
                                    <div class="flex flex-col h-full">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-university text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex-grow">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">
                                                Remaining Balance</p>
                                            <p class="text-3xl font-bold text-gray-800">
                                                {{ number_format($account->balance, 2) }}
                                                <span class="text-sm font-normal text-gray-500">LKR</span>
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <p class="text-xs text-gray-500">Click to manage</p>
                                                <span class="text-xs text-gray-400">
                                                    Created: {{ $account->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-full border border-gray-100">
                                <div class="flex flex-col h-full">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-university text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex-grow">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">
                                            Remaining Balance</p>
                                        <p class="text-3xl font-bold text-gray-800">
                                            {{ number_format($account->balance, 2) }}
                                            <span class="text-sm font-normal text-gray-500">LKR</span>
                                        </p>
                                    </div>
                                    <div class="mt-6 pt-4 border-t border-gray-100">
                                        <span class="text-xs text-gray-400">
                                            Created: {{ $account->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <div class="py-8">
                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-university text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No bank accounts</h3>
                        <p class="mt-2 text-sm text-gray-500">Get started by creating your first bank account.</p>
                        @can('create-banks')
                            <div class="mt-6">
                                <button wire:click="$set('showCreateModal', true)"
                                    class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create First Account
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Account Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Create New Bank Account</h3>
                        <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                            <div class="relative">
                                <input type="text" wire:model="newAccountName"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g. Petty Cash, Main Bank">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-university text-gray-400"></i>
                                </div>
                            </div>
                            @error('newAccountName')
                                <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tips -->
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <p class="text-xs text-blue-700 flex items-start gap-2">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <span>Tip: Use clear names like "Main Business Account" or "Emergency Fund"</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button wire:click="$set('showCreateModal', false)"
                            class="w-full sm:w-auto px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button wire:click="createAccount"
                            class="w-full sm:w-auto px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out font-medium">
                            Create Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Toast -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 z-50 animate-fade-in">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Prevent zoom on iOS input focus */
        @media screen and (max-width: 768px) {

            input[type="text"],
            input[type="number"] {
                font-size: 16px !important;
            }
        }

        /* Better touch targets */
        @media screen and (max-width: 768px) {

            button,
            a {
                min-height: 44px;
            }

            /* Card hover effects */
            .hover\\:shadow-md {
                transition: box-shadow 0.2s ease;
            }
        }

        /* Smooth transitions */
        .transition-shadow {
            transition: box-shadow 0.2s ease-in-out;
        }

        /* Better focus states */
        button:focus,
        input:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
</div>