<?php

namespace App\Livewire\Bank;

use Livewire\Component;

class BankIndex extends Component
{
    // Create Account Modal Properties
    public $showCreateModal = false;
    public $newAccountName = '';

    public function createAccount()
    {
        $this->authorize('create', \App\Models\BankAccount::class);
        $this->validate([
            'newAccountName' => 'required|string|max:255'
        ]);

        if (auth()->user()->quartz_id) {
            \App\Models\BankAccount::create([
                'quartz_id' => auth()->user()->quartz_id,
                'name' => $this->newAccountName,
                'balance' => 0
            ]);

            $this->showCreateModal = false;
            $this->newAccountName = '';
            session()->flash('message', 'Bank Account created successfully.');
        }
    }

    public function render()
    {
        $this->authorize('viewAny', \App\Models\BankAccount::class);
        $accounts = collect();
        $userDebt = 0;
        $userCredit = 0;

        if (auth()->user()->quartz_id) {
            $accounts = \App\Models\BankAccount::where('quartz_id', auth()->user()->quartz_id)->get();

            // Total Gross Debt (Pending splits)
            $userDebt = \App\Models\ExpenseSplit::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->sum('amount');

            // Total Credit (Unapplied deposits)
            // Sum of deposits - Sum of withdrawals linked to deposits
            $totalDeposits = \App\Models\BankTransaction::where('user_id', auth()->id())
                ->where('type', 'deposit')
                ->sum('amount');

            $totalApplied = \App\Models\BankTransaction::where('user_id', auth()->id())
                ->where('type', 'withdrawal')
                ->whereNotNull('source_transaction_id')
                ->sum('amount');

            $userCredit = max(0, $totalDeposits - $totalApplied);
        }

        return view('livewire.bank.bank-index', [
            'accounts' => $accounts,
            'userDebt' => $userDebt,
            'userCredit' => $userCredit
        ]);
    }
}
