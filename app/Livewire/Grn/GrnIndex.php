<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnIndex extends Component
{
    use \Livewire\WithPagination;

    public function render()
    {
        $this->authorize('viewAny', \App\Models\GrnSession::class);
        $grns = \App\Models\GrnSession::with(['quartz', 'shop', 'user', 'confirmedBy'])->latest()->paginate(10);
        return view('livewire.grn.grn-index', ['grns' => $grns]);
    }

    public function delete($id)
    {
        $grn = \App\Models\GrnSession::findOrFail($id);
        $this->authorize('delete', $grn);
        $grn->delete();
        session()->flash('message', 'GRN Session deleted successfully.');
    }
}
