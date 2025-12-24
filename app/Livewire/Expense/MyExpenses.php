<?php

namespace App\Livewire\Expense;

use Livewire\Component;

class MyExpenses extends Component
{
    public $bankAccounts;
    public $selectedBankId;
    public $paymentAmount;
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
            ->latest() // Visualization only
            ->get();
    }

    public function getTotalPendingProperty()
    {
        // Calculate total pending debt from Splits
        // Alternatively, use UserAccount balance if we trust it 100%. 
        // Let's use UserAccount as the source of truth for Debt.
        $userAccount = \App\Models\UserAccount::where('user_id', auth()->id())->first();
        return $userAccount ? $userAccount->balance : 0;
    }

    public function openPayModal()
    {
        $this->paymentAmount = $this->totalPending > 0 ? $this->totalPending : '';
        $this->showPayModal = true;
    }

    public function makePayment()
    {
        $this->validate([
            'selectedBankId' => 'required|exists:bank_accounts,id',
            'paymentAmount' => 'required|numeric|min:0.01'
        ]);

        $amountToPay = (float) $this->paymentAmount;

        \Illuminate\Support\Facades\DB::transaction(function () use ($amountToPay) {
            $userId = auth()->id();

            // 1. Create Bank Deposit (The actual money movement)
            \App\Models\BankTransaction::create([
                'bank_account_id' => $this->selectedBankId,
                'user_id' => $userId,
                'amount' => $amountToPay,
                'transaction_date' => now(),
                'description' => "Expense Payment (Waterfall Clearance)",
                'type' => 'deposit'
            ]);

            // 2. Update Bank Balance
            \App\Models\BankAccount::find($this->selectedBankId)->increment('balance', $amountToPay);

            // 3. Reduce User Debt (Global Balance)
            $userAccount = \App\Models\UserAccount::firstOrCreate(
                ['user_id' => $userId],
                ['balance' => 0]
            );
            $userAccount->decrement('balance', $amountToPay);

            // 4. Waterfall Reconciliation: Mark splits as paid starting from oldest
            $remainingPayment = $amountToPay;

            // Fetch pending splits ordered by oldest first
            $pendingSplits = \App\Models\ExpenseSplit::where('user_id', $userId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($pendingSplits as $split) {
                if ($remainingPayment <= 0) break;

                // For now, simpler logic: If we have enough to fully pay a split, pay it.
                // Partial payments strictly on splits might be complex if we want to track "partial".
                // User requirement: "if user deposit... then money is ok... show as paid"
                // Let's assume we fully clear splits if we have coverage.

                if ($remainingPayment >= $split->amount) {
                    $split->update([
                        'status' => 'paid',
                        'paid_at' => now()
                    ]);
                    $remainingPayment -= $split->amount;
                } else {
                    // Partial coverage? 
                    // Option A: Leave pending.
                    // Option B: Mark partial? Schema doesn't support partial status easily without 'amount_paid' column.
                    // Let's stick to: Only mark PAID if fully covered. 
                    // The UserAccount balance handles the "Credit/Partial" reality numerically.
                    // The Splits status is just a flag for "Is this specific item settled?".
                    // So if I owe 100, pay 50. Debt is 50. Split is still "Pending" (visual cue).
                    break;
                }
            }
        });

        $this->showPayModal = false;
        $this->reset(['selectedBankId', 'paymentAmount']);
        session()->flash('message', 'Payment processed successfully.');
    }

    public function render()
    {
        return view('livewire.expense.my-expenses', [
            'expenses' => $this->expenses,
            'totalPending' => $this->totalPending
        ]);
    }
}
