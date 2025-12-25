<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';
        $quartzId = $user->quartz_id;

        // --- PERSONAL STATS ---
        $debt = \App\Models\ExpenseSplit::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        $totalDeposits = \App\Models\BankTransaction::where('user_id', $user->id)
            ->where('type', 'deposit')
            ->sum('amount');

        $totalApplied = \App\Models\BankTransaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->whereNotNull('source_transaction_id')
            ->sum('amount');

        $credit = max(0, $totalDeposits - $totalApplied);

        // --- CHART DATA: Personal (Last 7 Days) ---
        $personalChartData = [
            'labels' => [],
            'data' => []
        ];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $spent = \App\Models\ExpenseSplit::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->sum('amount');
            $personalChartData['labels'][] = now()->subDays($i)->format('M d');
            $personalChartData['data'][] = $spent;
        }

        // --- NEW 4 METRICS ---
        $monthlySpend = \App\Models\ExpenseSplit::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Lifetime deposits is $totalDeposits already calculated above? 
        // Let's re-confirm/re-use $totalDeposits which is sum of 'deposit' type transactions.
        $lifetimeDeposits = $totalDeposits;

        $uniqueItemsCount = \App\Models\ExpenseSplit::where('expense_splits.user_id', $user->id)
            ->join('grn_items', 'expense_splits.grn_item_id', '=', 'grn_items.id')
            ->distinct('grn_items.item_id')
            ->count('grn_items.item_id');

        $lastShop = \App\Models\ExpenseSplit::where('expense_splits.user_id', $user->id)
            ->join('grn_items', 'expense_splits.grn_item_id', '=', 'grn_items.id')
            ->join('grn_sessions', 'grn_items.grn_session_id', '=', 'grn_sessions.id')
            ->join('shops', 'grn_sessions.shop_id', '=', 'shops.id')
            ->latest('expense_splits.created_at')
            ->value('shops.name') ?? 'N/A';

        $recentPersonalExpenses = \App\Models\ExpenseSplit::where('user_id', $user->id)
            ->with(['grnItem.item', 'grnItem.grnSession.shop'])
            ->latest()
            ->take(5)
            ->get();

        // --- QUARTZ STATS ---
        $quartzStats = null;
        if ($quartzId) {
            $quartz = \App\Models\Quartz::find($quartzId);

            // Chart: Shop-wise expenditure for this quartz (All time)
            $shopExpenditure = \App\Models\GrnSession::where('quartz_id', $quartzId)
                ->where('status', 'confirmed')
                ->with('shop')
                ->get()
                ->groupBy('shop.name')
                ->map(fn($group) => $group->sum(fn($s) => $s->grnItems->sum('total_price')));

            $quartzStats = [
                'name' => $quartz->name,
                'total_debt' => \App\Models\ExpenseSplit::whereHas('user', function ($q) use ($quartzId) {
                    $q->where('quartz_id', $quartzId);
                })->where('status', 'pending')->sum('amount'),
                'bank_balance' => \App\Models\BankAccount::where('quartz_id', $quartzId)->sum('balance'),
                'recent_sessions' => \App\Models\GrnSession::where('quartz_id', $quartzId)
                    ->with('shop')
                    ->latest()
                    ->take(5)
                    ->get(),
                'chart_labels' => $shopExpenditure->keys()->toArray(),
                'chart_data' => $shopExpenditure->values()->toArray()
            ];
        }

        // --- ADMIN STATS ---
        $adminStats = null;
        if ($isAdmin) {
            $adminStats = [
                'total_system_debt' => \App\Models\ExpenseSplit::where('status', 'pending')->sum('amount'),
                'total_liquidity' => \App\Models\BankAccount::sum('balance'),
                'quartz_count' => \App\Models\Quartz::count(),
                'user_count' => \App\Models\User::count(),
                'recent_global_activity' => \App\Models\GrnSession::with(['quartz', 'shop'])
                    ->latest()
                    ->take(5)
                    ->get()
            ];
        }

        return view('livewire.dashboard', [
            'debt' => $debt,
            'credit' => $credit,
            'monthlySpend' => $monthlySpend,
            'lifetimeDeposits' => $lifetimeDeposits,
            'uniqueItemsCount' => $uniqueItemsCount,
            'lastShop' => $lastShop,
            'personalChartData' => $personalChartData,
            'recentPersonalExpenses' => $recentPersonalExpenses,
            'quartzStats' => $quartzStats,
            'adminStats' => $adminStats,
            'isAdmin' => $isAdmin
        ]);
    }
}
