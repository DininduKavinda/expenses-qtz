<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnShow extends Component
{
    public \App\Models\GrnSession $grn;

    public function mount(\App\Models\GrnSession $grn)
    {
        $this->grn = $grn->load(['quartz', 'user', 'confirmedBy', 'grnItems.item', 'images']);
    }

    public function confirmSession()
    {
        if ($this->grn->status !== 'confirmed') {
            // Use the model logic which handles transaction and splitting
            $this->grn->processConfirmation(auth()->id());

            session()->flash('message', 'GRN Session confirmed successfully.');
        }
    }

    public function render()
    {
        return view('livewire.grn.grn-show');
    }
}
