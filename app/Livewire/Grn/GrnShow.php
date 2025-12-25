<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnShow extends Component
{
    public \App\Models\GrnSession $grn;
    public $bankAccounts;
    public $selectedBankId;

    public function mount(\App\Models\GrnSession $grn)
    {
        $this->authorize('view', $grn);
        $this->grn = $grn->load(['quartz', 'user', 'confirmedBy', 'grnItems.item', 'images']);

        if (auth()->user()->quartz_id) {
            $this->bankAccounts = \App\Models\BankAccount::where('quartz_id', auth()->user()->quartz_id)->get();
        } else {
            $this->bankAccounts = collect();
        }
    }

    public function confirmSession()
    {
        $this->authorize('confirm', $this->grn);
        if ($this->grn->status !== 'confirmed') {
            // $this->grn->update(['bank_account_id' => $this->selectedBankId]);

            // Use the model logic which handles transaction and splitting
            $this->grn->processConfirmation(auth()->id());

            session()->flash('message', 'GRN Session confirmed and paid successfully.');
        }
    }

    public function render()
    {
        return view('livewire.grn.grn-show');
    }
}
