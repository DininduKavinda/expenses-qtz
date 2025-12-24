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
        // Business Rule: Can only confirm if pending.
        // User check (e.g. Creator cannot confirm? Or anyone can confirm? Assuming logged in user can confirm if needed).
        // Let's assume for now any user can confirm if it's pending.

        if ($this->grn->status !== 'confirmed') {
            $this->grn->update([
                'status' => 'confirmed',
                'confirmed_by' => auth()->id(),
                'confirmed_at' => now(),
            ]);
            session()->flash('message', 'GRN Session confirmed successfully.');
        }
    }

    public function render()
    {
        return view('livewire.grn.grn-show');
    }
}
