<div>
    <!-- Header Section -->
    <div class="py-4 md:py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Header -->
            <div class="md:hidden space-y-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800">
                            {{ auth()->user()->role?->slug === 'admin' ? 'Global Expense Audit' : 'My Expenses' }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ auth()->user()->role?->slug === 'admin' ? 'Monitor all expenses' : 'Track your expense contributions' }}
                        </p>
                    </div>
                </div>

                <!-- Mobile Debt Card -->
                <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-red-600 uppercase">Current Debt</p>
                            <p class="text-xl font-bold text-red-700 mt-1">
                                LKR {{ number_format($totalPending, 2) }}
                            </p>
                            @if($availableCredit > 0)
                                <p class="text-xs text-green-600 mt-1">
                                    Credit Available: LKR {{ number_format($availableCredit, 2) }}
                                </p>
                            @endif
                        </div>
                        <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-red-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    @if($availableCredit > 0)
                        <button wire:click="openApplyDepositModal"
                            class="bg-green-50 text-green-700 border border-green-200 rounded-lg p-3 text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Apply Credit
                        </button>
                    @endif
                    <button wire:click="openPayModal"
                        class="bg-indigo-600 text-white rounded-lg p-3 text-sm font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        Pay Now
                    </button>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden md:flex flex-col md:flex-row md:items-center justify-between gap-6">
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
                                    <i class="fas fa-money-bill-wave text-red-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pay Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($availableCredit > 0)
                            <button wire:click="openApplyDepositModal"
                                class="px-4 py-3 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 font-medium text-sm transition duration-150 ease-in-out flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Apply Credit (LKR {{ number_format($availableCredit, 2) }})
                            </button>
                        @endif
                        <button wire:click="openPayModal"
                            class="px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm shadow-sm hover:shadow transition duration-150 ease-in-out flex items-center justify-center gap-2">
                            <i class="fas fa-credit-card"></i>
                            Make Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pb-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters Section -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <!-- Mobile Filter Toggle -->
                <button onclick="toggleFilters()"
                    class="md:hidden w-full flex items-center justify-between p-3 mb-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="font-medium text-gray-700 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Filters
                    </span>
                    <i id="filterIcon" class="fas fa-chevron-down transition-transform"></i>
                </button>

                <div id="filterSection" class="hidden md:block">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date From</label>
                            <div class="relative">
                                <input type="date" wire:model.live="dateFrom"
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date To</label>
                            <div class="relative">
                                <input type="date" wire:model.live="dateTo"
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                            <select wire:model.live="selectedStatus"
                                class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="searchQuery"
                                    placeholder="Search item or shop..."
                                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 pl-10">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <!-- Header -->
                <div class="px-4 md:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Expense History</h3>
                            <p class="text-sm text-gray-500 mt-1">All your shared expenses and payment status</p>
                        </div>
                        @if($expenses->total() > 0)
                            <div class="text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                {{ $expenses->count() }} of {{ $expenses->total() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Cards View -->
                <div class="md:hidden">
                    @if($expenses->isNotEmpty())
                        <div class="divide-y divide-gray-200">
                            @foreach($expenses as $expense)
                                <div class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <!-- Card Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                {{ $expense->grnItem->item->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $expense->grnItem->grnSession?->session_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <span class="text-sm font-bold {{ $expense->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                            LKR {{ number_format($expense->amount, 2) }}
                                        </span>
                                    </div>

                                    <!-- Details -->
                                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded-full bg-purple-100 flex items-center justify-center">
                                                <i class="fas fa-store text-purple-600 text-[10px]"></i>
                                            </div>
                                            <span class="text-gray-600 truncate">
                                                {{ $expense->grnItem->grnSession?->shop->name ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="h-4 w-4 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-box text-blue-600 text-[10px]"></i>
                                            </div>
                                            <span class="text-gray-600">Qty: {{ $expense->grnItem->quantity }}</span>
                                        </div>
                                    </div>

                                    <!-- Status & Actions -->
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                        @if($expense->status === 'paid')
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid
                                                </span>
                                                <span class="text-[10px] text-gray-400">
                                                    {{ optional($expense->paid_at)->format('M d') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-600 border border-red-100">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        @endif
                                        
                                        @if(auth()->user()->role?->slug === 'admin')
                                            <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                                {{ substr($expense->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="px-6 py-12 text-center">
                            <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-700">No expenses found</p>
                            <p class="text-sm text-gray-500 mt-1 max-w-xs mx-auto">Try adjusting your search criteria</p>
                        </div>
                    @endif
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
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
                                            {{ $expense->grnItem->grnSession?->session_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $expense->grnItem->grnSession?->session_date->format('D') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center mr-3 flex-shrink-0">
                                                <i class="fas fa-shopping-basket text-gray-400"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $expense->grnItem->item->name }}
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-0.5 rounded">
                                                        GRN #{{ $expense->grnItem->grnSession?->id }}
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
                                            {{ $expense->grnItem->grnSession?->shop->name ?? '-' }}
                                        </div>
                                    </td>
                                    @if(auth()->user()->role?->slug === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2 text-indigo-700 font-bold text-xs">
                                                    {{ substr($expense->user->name, 0, 1) }}
                                                </div>
                                                <div class="text-sm text-gray-900 font-medium">
                                                    {{ $expense->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-bold text-gray-900">
                                            LKR {{ number_format($expense->amount, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($expense->status === 'paid')
                                            <div class="flex flex-col items-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Paid
                                                </span>
                                                <div class="text-[10px] text-gray-400 mt-1 italic">
                                                    {{ optional($expense->paid_at)->format('M d, Y') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-600 border border-red-100 shadow-sm">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role?->slug === 'admin' ? 6 : 5 }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-receipt text-3xl"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-700">No expenses found</p>
                                            <p class="text-sm text-gray-500 mt-1 max-w-xs mx-auto">We couldn't find any expenses matching your filters. Try adjusting your search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($expenses->hasPages())
                    <div class="px-4 md:px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $expenses->links() }}
                    </div>
                @endif
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
                            <i class="fas fa-times"></i>
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
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
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
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
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
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mb-4">
                            <i class="fas fa-check-circle text-2xl"></i>
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
                                <i class="fas fa-info-circle mr-2"></i>
                                Pending Debt
                            </div>
                            <p class="text-lg font-bold text-blue-800">LKR {{ number_format($totalPending, 2) }}</p>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
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
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        </div>
    @endif

    <script>
        function toggleFilters() {
            const filterSection = document.getElementById('filterSection');
            const filterIcon = document.getElementById('filterIcon');

            if (filterSection.classList.contains('hidden')) {
                filterSection.classList.remove('hidden');
                filterIcon.classList.remove('fa-chevron-down');
                filterIcon.classList.add('fa-chevron-up');
            } else {
                filterSection.classList.add('hidden');
                filterIcon.classList.remove('fa-chevron-up');
                filterIcon.classList.add('fa-chevron-down');
            }
        }

        // Close filters when clicking outside on mobile
        document.addEventListener('click', function (event) {
            const filterSection = document.getElementById('filterSection');
            const filterToggle = document.querySelector('[onclick="toggleFilters()"]');

            if (window.innerWidth < 768 && filterSection && !filterSection.contains(event.target) &&
                !filterToggle.contains(event.target)) {
                if (!filterSection.classList.contains('hidden')) {
                    filterSection.classList.add('hidden');
                    const filterIcon = document.getElementById('filterIcon');
                    filterIcon.classList.remove('fa-chevron-up');
                    filterIcon.classList.add('fa-chevron-down');
                }
            }
        });
    </script>

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
            input[type="date"],
            input[type="text"],
            select {
                font-size: 16px !important;
            }
        }

        /* Better touch targets */
        @media screen and (max-width: 768px) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Custom scrollbar for mobile */
        @media screen and (max-width: 768px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Smooth transitions */
        .transition-transform {
            transition: transform 0.2s ease-in-out;
        }
    </style>
</div>