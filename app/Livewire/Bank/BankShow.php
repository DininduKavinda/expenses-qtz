<?php

namespace App\Livewire\Bank;

use Livewire\Component;

class BankShow extends Component
{
    public \App\Models\BankAccount $bankAccount;

    // Add Money Modal properties
    public $showAddMoneyModal = false;
    public $user_id;
    public $amount;
    public $transaction_date;
    public $description;
    public $users;

    // Filters
    public $filterUser = '';
    public $filterDate = '';

    public function getTransactionsProperty()
    {
        return $this->bankAccount->transactions()
            ->with('user')
            ->when($this->filterUser, function ($q) {
                return $q->where('user_id', $this->filterUser);
            })
            ->when($this->filterDate, function ($q) {
                return $q->whereDate('transaction_date', $this->filterDate);
            })
            ->latest()
            ->get();
    }

    public function mount(\App\Models\BankAccount $bankAccount)
    {
        $this->authorize('view', $bankAccount);
        $this->bankAccount = $bankAccount;
        $this->user_id = auth()->id();
        $this->transaction_date = now()->format('Y-m-d');

        if (auth()->user()->quartz_id) {
            $this->users = \App\Models\User::where('quartz_id', auth()->user()->quartz_id)->get();
        } else {
            $this->users = collect();
        }
    }

    public function addMoney()
    {
        $this->authorize('update', $this->bankAccount);
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () {
            // 1. Create Deposit Transaction
            $this->bankAccount->transactions()->create([
                'user_id' => $this->user_id,
                'amount' => $this->amount,
                'transaction_date' => $this->transaction_date,
                'description' => $this->description,
                'type' => 'deposit'
            ]);

            // 2. Increment Bank Balance
            $this->bankAccount->increment('balance', $this->amount);

            // 3. Decrement User Debt (Balance) - REMOVED per user request
            // Debt is now only reduced when credits are applied in MyExpenses.
        });

        $this->showAddMoneyModal = false;
        $this->reset(['amount', 'description']);
        $this->bankAccount->refresh(); // Refresh balance

        session()->flash('message', 'Money added successfully.');
    }

    public function render()
    {
        // Calculate User Status Table Data
        $userStatus = collect();
        foreach ($this->users as $user) {
            // Gross Debt = Sum of pending expense splits
            $pendingDebt = \App\Models\ExpenseSplit::where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount');

            // Total Deposits
            $totalDeposits = \App\Models\BankTransaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->sum('amount');

            // Total Applied (Sum of withdrawals linked to deposits)
            $totalApplied = \App\Models\BankTransaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->whereNotNull('source_transaction_id')
                ->sum('amount');

            $unappliedCredit = max(0, $totalDeposits - $totalApplied);

            $userStatus->push([
                'name' => $user->name,
                'total_share' => $pendingDebt + $totalApplied,
                'paid' => $totalApplied,
                'pending' => $pendingDebt,
                'unapplied_credit' => $unappliedCredit,
                'is_current' => $user->id === auth()->id()
            ]);
        }

        return view('livewire.bank.bank-show', [
            'userStatus' => $userStatus,
            'transactions' => $this->transactions
        ]);
    }
}
