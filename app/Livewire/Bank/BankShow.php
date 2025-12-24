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

            // 3. Decrement User Debt (Balance)
            // Note: Balance is Debt. So paying money reduces balance.
            $userAccount = \App\Models\UserAccount::firstOrCreate(
                ['user_id' => $this->user_id],
                ['balance' => 0] // Default 0 if new
            );
            $userAccount->decrement('balance', $this->amount);
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
            $pendingDebt = $user->account->balance ?? 0;
            // Get Total Paid (Deposits) for this Bank Account? Or Total General?
            // User asked: "in bank account show how mutch they have to pay and how much logged user paid"
            // Assuming this Bank Account is the main place.
            // Let's count deposits to THIS bank account for simplicity, or ALL quartz accounts?
            // "in bank account show..." implies strictly this context.
            // But debt is global per UserAccount.
            // Logic: Total Share = Remaining Debt + Total Paid.
            // Paid should be ALL deposits to ANY Quartz account really, if debt is global.
            // Let's assume Paid = Sum of transactions type 'deposit' by this user.

            $totalPaid = \App\Models\BankTransaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->sum('amount');

            $totalShare = $pendingDebt + $totalPaid;

            $userStatus->push([
                'name' => $user->name,
                'total_share' => $totalShare,
                'paid' => $totalPaid,
                'pending' => $pendingDebt,
                'is_current' => $user->id === auth()->id()
            ]);
        }

        return view('livewire.bank.bank-show', [
            'userStatus' => $userStatus,
            'transactions' => $this->transactions
        ]);
    }
}
