<div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ auth()->user()->role?->slug === 'admin' ? __('Global Bank Audit') : __('Bank Accounts') }}
                </h2>
                @can('create-banks')
                    <button wire:click="$set('showCreateModal', true)"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out">
                        Create Account
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Financial Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 mb-6">
                <!-- Financial Status Header -->
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($accounts as $account)
                        @can('view-banks')
                            <a href="{{ route('banks.show', $account) }}" class="block">
                                <div
                                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition duration-150 ease-in-out h-full border border-gray-100">
                                    <div class="flex flex-col h-full">
                                        <h3 class="text-lg font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                        <div class="mt-4 flex-grow">
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">
                                                Remaining
                                                Balance</p>
                                            <p class="text-3xl font-bold text-gray-800">
                                                {{ number_format($account->balance, 2) }}
                                                <span class="text-sm font-normal text-gray-500">LKR</span>
                                            </p>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-100">
                                            <p class="text-xs text-gray-500">Click to manage</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-full border border-gray-100">
                                <div class="flex flex-col h-full">
                                    <h3 class="text-lg font-bold text-gray-900 truncate">{{ $account->name }}</h3>
                                    <div class="mt-4 flex-grow">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">
                                            Remaining
                                            Balance</p>
                                        <p class="text-3xl font-bold text-gray-800">
                                            {{ number_format($account->balance, 2) }}
                                            <span class="text-sm font-normal text-gray-500">LKR</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <div class="py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No bank accounts</h3>
                        <p class="mt-2 text-sm text-gray-500">Get started by creating your first bank account.</p>
                        @can('create-banks')
                            <div class="mt-6">
                                <button wire:click="$set('showCreateModal', true)"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Account
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Bank Account</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                            <input type="text" wire:model="newAccountName"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g. Petty Cash, Main Bank">
                            @error('newAccountName')
                                <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showCreateModal', false)"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button wire:click="createAccount"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Create Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>