<?php

namespace App\Livewire\Expense;

use Livewire\Component;

class MyExpenses extends Component
{
    public $bankAccounts;
    public $selectedBankId;
    public $paymentAmount;
    public $showPayModal = false;
    public $showApplyDepositModal = false;
    public $selectedDepositId;

    public function mount()
    {
        $user = auth()->user();
        $isAdmin = $user->role && $user->role->slug === 'admin';

        if ($isAdmin) {
            $this->bankAccounts = \App\Models\BankAccount::all();
        } elseif ($user && $user->quartz_id) {
            $this->bankAccounts = \App\Models\BankAccount::where('quartz_id', $user->quartz_id)->get();
        } else {
            $this->bankAccounts = collect();
        }
    }

    public function getExpensesProperty()
    {
        $isAdmin = auth()->user()->role && auth()->user()->role->slug === 'admin';
        $query = \App\Models\ExpenseSplit::with(['grnItem.grnSession.shop', 'grnItem.item', 'grnItem.grnSession', 'user']);

        if (!$isAdmin) {
            $query->where('user_id', auth()->id());
        }

        return $query->latest()->get();
    }

    public function getTotalPendingProperty()
    {
        $userAccount = \App\Models\UserAccount::where('user_id', auth()->id())->first();
        return $userAccount ? $userAccount->balance : 0;
    }

    public function getAvailableDepositsProperty()
    {
        // Fetch deposits that have unapplied balance
        // We look at the sum of withdrawals that point to this deposit as source
        return \App\Models\BankTransaction::where('user_id', auth()->id())
            ->where('type', 'deposit')
            ->withSum('appliedPayments', 'amount')
            ->get()
            ->filter(function ($tx) {
                return ($tx->amount - ($tx->applied_payments_sum_amount ?? 0)) > 0.01;
            });
    }

    public function getAvailableCreditProperty()
    {
        return $this->availableDeposits->sum(function ($tx) {
            return $tx->amount - ($tx->applied_payments_sum_amount ?? 0);
        });
    }

    public function openPayModal()
    {
        $this->paymentAmount = $this->totalPending > 0 ? $this->totalPending : '';
        $this->showPayModal = true;
    }

    public function makePayment()
    {
        $this->authorize('create', \App\Models\BankTransaction::class);
        $this->validate([
            'selectedBankId' => 'required|exists:bank_accounts,id',
            'paymentAmount' => 'required|numeric|min:0.01'
        ]);

        $amountToPay = (float) $this->paymentAmount;

        \Illuminate\Support\Facades\DB::transaction(function () use ($amountToPay) {
            $userId = auth()->id();

            // 1. Create Bank Deposit
            $transaction = \App\Models\BankTransaction::create([
                'bank_account_id' => $this->selectedBankId,
                'user_id' => $userId,
                'amount' => $amountToPay,
                'transaction_date' => now(),
                'description' => "Expense Payment (Waterfall Clearance)",
                'type' => 'deposit'
            ]);

            // 2. Increment Bank Account Balance
            \App\Models\BankAccount::find($this->selectedBankId)->increment('balance', $amountToPay);

            // 3. Waterfall Reconciliation - REMOVED per user request
            // We no longer automatically pay off expenses on deposit.
            // Debt reduction is handled in applyCredit.
        });

        $this->showPayModal = false;
        $this->reset(['selectedBankId', 'paymentAmount']);
        session()->flash('message', 'Payment processed successfully.');
    }

    public function openApplyDepositModal()
    {
        $this->showApplyDepositModal = true;
    }

    public function applyCredit()
    {
        $this->authorize('process-payments');

        $availableCredit = $this->availableCredit;

        if ($availableCredit <= 0) {
            $this->showApplyDepositModal = false;
            session()->flash('message', 'You have no available credit to apply.');
            return;
        }

        if ($availableCredit < $this->totalPending) {
            $this->showApplyDepositModal = false;
            session()->flash('message', 'You do not have enough credit to apply.');
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($availableCredit) {
            $userId = auth()->id();

            // Fetch pending splits
            $pendingSplits = \App\Models\ExpenseSplit::where('user_id', $userId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get();

            // Fetch available deposits
            $deposits = $this->availableDeposits->sortBy('transaction_date');

            $currentDepositIndex = 0;
            $remainingCreditInTx = 0;
            $txToApply = null;

            foreach ($pendingSplits as $split) {
                // Find a deposit with balance
                while ($remainingCreditInTx <= 0.01 && $currentDepositIndex < $deposits->count()) {
                    $txToApply = $deposits->values()[$currentDepositIndex];
                    $remainingCreditInTx = $txToApply->amount - ($txToApply->applied_payments_sum_amount ?? 0);
                    $currentDepositIndex++;
                }

                if ($remainingCreditInTx <= 0.01) break; // No more credit

                if ($remainingCreditInTx >= $split->amount) {
                    // Create Withdrawal Transaction (The actual "payment" from bank)
                    $withdrawal = \App\Models\BankTransaction::create([
                        'bank_account_id' => $txToApply->bank_account_id,
                        'user_id' => $userId,
                        'amount' => $split->amount,
                        'transaction_date' => now(),
                        'description' => "Credit Application for Split #" . $split->id,
                        'type' => 'withdrawal',
                        'source_transaction_id' => $txToApply->id // Link to original deposit
                    ]);

                    // Deduct from Bank Account
                    \App\Models\BankAccount::find($txToApply->bank_account_id)->decrement('balance', $split->amount);

                    // Mark Split as Paid and link to the WITHDRAWAL
                    $split->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'bank_transaction_id' => $withdrawal->id
                    ]);

                    $remainingCreditInTx -= $split->amount;

                    // Update User Account (Reduce Debt)
                    $userAccount = \App\Models\UserAccount::firstOrCreate(
                        ['user_id' => $userId],
                        ['balance' => 0]
                    );
                    $userAccount->decrement('balance', $split->amount);

                    // Trigger completion check for the GRN session
                    if ($split->grnItem && $split->grnItem->grnSession) {
                        $split->grnItem->grnSession->checkCompletionStatus();
                    }
                } else {
                    // Partial coverage - break for now as per user logic
                    break;
                }
            }
        });

        $this->showApplyDepositModal = false;
        session()->flash('message', 'Credit applied successfully to your oldest expenses.');
    }

    public function render()
    {
        $this->authorize('viewAny', \App\Models\ExpenseSplit::class);

        return view('livewire.expense.my-expenses', [
            'expenses' => $this->expenses,
            'totalPending' => $this->totalPending,
            'availableCredit' => $this->availableCredit
        ]);
    }
}
