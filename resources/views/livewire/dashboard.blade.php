<div>
    <livewire:layout.navigation />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">Financial Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
                            <h3 class="text-white/80 text-sm font-medium uppercase tracking-wider">Total Pending Debt
                            </h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold">{{ number_format($debt, 2) }}</span>
                                <span class="text-white/60">LKR</span>
                            </div>
                            <p class="mt-2 text-xs text-white/70">
                                This is your full share of unpaid expenses.
                            </p>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                            <h3 class="text-white/80 text-sm font-medium uppercase tracking-wider">Available Credit</h3>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="text-3xl font-bold">{{ number_format($credit, 2) }}</span>
                                <span class="text-white/60">LKR</span>
                            </div>
                            <p class="mt-2 text-xs text-white/70">
                                Deposits ready to be applied to expenses.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>