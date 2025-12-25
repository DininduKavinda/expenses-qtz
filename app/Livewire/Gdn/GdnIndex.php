<?php

namespace App\Livewire\Gdn;

use Livewire\Component;

class GdnIndex extends Component
{
    public function render()
    {
        $this->authorize('viewAny', \App\Models\Gdn::class);

        $gdns = \App\Models\Gdn::where('quartz_id', auth()->user()->quartz_id)
            ->with(['user', 'gdnItems.grnItem.item'])
            ->latest()
            ->get();

        return view('livewire.gdn.gdn-index', [
            'gdns' => $gdns
        ]);
    }
}
