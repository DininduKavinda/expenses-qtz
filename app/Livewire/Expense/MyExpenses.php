<?php

namespace App\Livewire\Expense;

use Livewire\Component;

class MyExpenses extends Component
{
    public $bankAccounts;
    public $selectedSplitId;
    public $selectedBankId;
    public $showPayModal = false;

    public function mount()
    {
        if (auth()->user()->quartz_id) {
            $this->bankAccounts = \App\Models\BankAccount::where('quartz_id', auth()->user()->quartz_id)->get();
        } else {
            $this->bankAccounts = collect();
        }
    }

    public function getExpensesProperty()
    {
        return \App\Models\ExpenseSplit::where('user_id', auth()->id())
            ->with(['grnItem.grnSession.shop', 'grnItem.item', 'grnItem.grnSession'])
            ->latest()
            ->get();
    }

    public function openPayModal($splitId)
    {
        $this->selectedSplitId = $splitId;
        $this->showPayModal = true;
    }

    public function confirmPayment()
    {
        $this->validate([
            'selectedBankId' => 'required|exists:bank_accounts,id',
            'selectedSplitId' => 'required|exists:expense_splits,id'
        ]);

        $split = \App\Models\ExpenseSplit::find($this->selectedSplitId);
        if ($split->status === 'paid') {
            $this->showPayModal = false;
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($split) {
            // 1. Mark Split as Paid
            $split->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            // 2. Create Bank Deposit
            // Description: "Payment for Item: X in GRN: Y"
            $description = "Payment for " . $split->grnItem->item->name . " (GRN #" . $split->grnItem->grnSession->id . ")";

            \App\Models\BankTransaction::create([
                'bank_account_id' => $this->selectedBankId,
                'user_id' => auth()->id(),
                'amount' => $split->amount,
                'transaction_date' => now(),
                'description' => $description,
                'type' => 'deposit'
            ]);

            // 3. Update Bank Balance
            \App\Models\BankAccount::find($this->selectedBankId)->increment('balance', $split->amount);

            // 4. Reduce User Debt
            $userAccount = \App\Models\UserAccount::where('user_id', auth()->id())->first();
            if ($userAccount) {
                $userAccount->decrement('balance', $split->amount);
            }
        });

        $this->showPayModal = false;
        $this->reset(['selectedSplitId', 'selectedBankId']);
        session()->flash('message', 'Payment successful.');
    }

    public function render()
    {
        return view('livewire.expense.my-expenses', [
            'expenses' => $this->expenses
        ]);
    }
}
