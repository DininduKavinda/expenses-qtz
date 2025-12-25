<div>
    <!-- Header Section -->
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                        {{ auth()->user()->role?->slug === 'admin' ? __('Global Expense Audit') : __('My Expenses') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">
                        {{ auth()->user()->role?->slug === 'admin' ? 'Monitor all shared expense contributions across the system' : 'Track and manage your expense contributions' }}
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <!-- Debt Card -->
                    <div class="px-5 py-4 bg-red-50 border border-red-100 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-red-600 uppercase tracking-widest">Current Debt</span>
                                <div class="text-2xl font-bold text-red-700 mt-1">
                                    LKR {{ number_format($totalPending, 2) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pay Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($availableCredit > 0)
                            <button wire:click="openApplyDepositModal"
                                class="px-6 py-3.5 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 font-semibold shadow-sm transition duration-150 ease-in-out flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Apply Credit (LKR {{ number_format($availableCredit, 2) }})
                            </button>
                        @endif
                        <button wire:click="openPayModal"
                            class="px-6 py-3.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold shadow-md hover:shadow-lg transition duration-150 ease-in-out flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Make Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Info Banner -->
            <div class="mb-6 bg-blue-50 border border-blue-100 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-blue-700">
                        Expenses are marked as <strong>Paid</strong> automatically starting from your oldest debts when you make a payment.
                        The payment will be deposited to the selected bank account.
                    </p>
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Expense History</h3>
                    <p class="text-sm text-gray-500 mt-1">All your shared expenses and payment status</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Item Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Shop
                                </th>
                                @if(auth()->user()->role?->slug === 'admin')
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Member
                                    </th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Your Share
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($expenses as $expense)
                                <tr class="{{ $expense->status === 'paid' ? 'bg-gray-50' : 'hover:bg-gray-50' }} transition duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $expense->grnItem->grnSession->session_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $expense->grnItem->grnSession->session_date->format('D') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center mr-3 flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $expense->grnItem->item->name }}
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded">
                                                        GRN #{{ $expense->grnItem->grnSession->id }}
                                                    </span>
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        Qty: {{ $expense->grnItem->quantity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $expense->grnItem->grnSession->shop->name ?? '-' }}
                                        </div>
                                    </td>
                                    @if(auth()->user()->role?->slug === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                                    <span class="text-xs font-bold text-indigo-700">{{ substr($expense->user->name, 0, 1) }}</span>
                                                </div>
                                                <div class="text-sm text-gray-900 font-medium">
                                                    {{ $expense->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            LKR {{ number_format($expense->amount, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($expense->status === 'paid')
                                            <div class="flex flex-col items-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Paid
                                                </span>
                                                <div class="text-[10px] text-gray-400 mt-1">
                                                    {{ optional($expense->paid_at)->format('M d, Y') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role?->slug === 'admin' ? 6 : 5 }}" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">No expenses found</p>
                                            <p class="text-sm text-gray-400 mt-1">Your expense contributions will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pay Modal -->
    @if($showPayModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Make a Payment</h3>
                        <button wire:click="$set('showPayModal', false)" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6">
                        Deposit money to the bank account to clear your debts. Oldest debts are cleared first.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                            <select wire:model="selectedBankId"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Bank Account</option>
                                @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->name }} 
                                        <span class="text-gray-500">(Balance: LKR {{ number_format($account->balance, 2) }})</span>
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedBankId') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount (LKR)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    LKR
                                </span>
                                <input type="number" step="0.01" wire:model="paymentAmount"
                                    class="w-full pl-12 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="0.00">
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-xs text-gray-500">
                                    Total Pending: <span class="font-semibold">LKR {{ number_format($totalPending, 2) }}</span>
                                </div>
                                <button type="button" wire:click="$set('paymentAmount', {{ $totalPending }})"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Pay Full Amount
                                </button>
                            </div>
                            @error('paymentAmount') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Payment Preview -->
                        @if($paymentAmount > 0)
                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700">Payment Preview</span>
                                </div>
                                <div class="text-sm text-blue-600">
                                    @if($paymentAmount >= $totalPending)
                                        <p>✅ Will clear all {{ $expenses->where('status', 'pending')->count() }} pending expenses</p>
                                    @else
                                        <p>⚠️ Will partially clear oldest pending expenses</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showPayModal', false)"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button wire:click="makePayment"
                            class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Confirm Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Apply Credit Modal -->
    @if($showApplyDepositModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Apply Available Credit</h3>
                        <button wire:click="$set('showApplyDepositModal', false)" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900">LKR {{ number_format($availableCredit, 2) }}</h4>
                        <p class="text-sm text-gray-500">Total Available Credit</p>
                    </div>

                    <p class="text-sm text-gray-600 mb-6 text-center">
                        Would you like to apply your available credit to settle your oldest pending expenses?
                    </p>

                    @if($totalPending > 0)
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
                            <div class="flex items-center text-sm font-medium text-blue-700 mb-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending Debt
                            </div>
                            <p class="text-lg font-bold text-blue-800">LKR {{ number_format($totalPending, 2) }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showApplyDepositModal', false)"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button wire:click="applyCredit"
                            class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out font-bold">
                            Apply Credit Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 z-50 animate-fade-in">
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
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
</style>
</div>

