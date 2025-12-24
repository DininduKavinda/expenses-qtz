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
        $accounts = collect();
        if (auth()->user()->quartz_id) {
            $accounts = \App\Models\BankAccount::where('quartz_id', auth()->user()->quartz_id)->get();
        }

        return view('livewire.bank.bank-index', [
            'accounts' => $accounts
        ]);
    }
}
