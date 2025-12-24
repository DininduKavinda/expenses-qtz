<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnIndex extends Component
{
    use \Livewire\WithPagination;

    public function render()
    {
        $grns = \App\Models\GrnSession::with(['quartz', 'shop', 'user', 'confirmedBy'])->latest()->paginate(10);
        return view('livewire.grn.grn-index', ['grns' => $grns]);
    }

    public function delete($id)
    {
        \App\Models\GrnSession::find($id)->delete();
        session()->flash('message', 'GRN Session deleted successfully.');
    }
}
