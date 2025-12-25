<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Dashboard is generally accessible if logged in, but we can add a check
        // if ($user->hasPermission('view-dashboard')) { ... }

        // Gross Debt = Sum of pending expense splits
        $debt = \App\Models\ExpenseSplit::where('user_id', $userId)
            ->where('status', 'pending')
            ->sum('amount');

        // Total Deposits
        $totalDeposits = \App\Models\BankTransaction::where('user_id', $userId)
            ->where('type', 'deposit')
            ->sum('amount');

        // Total Applied (Sum of withdrawals linked to deposits)
        $totalApplied = \App\Models\BankTransaction::where('user_id', $userId)
            ->where('type', 'withdrawal')
            ->whereNotNull('source_transaction_id')
            ->sum('amount');

        $credit = max(0, $totalDeposits - $totalApplied);

        return view('livewire.dashboard', [
            'debt' => $debt,
            'credit' => $credit
        ]);
    }
}
