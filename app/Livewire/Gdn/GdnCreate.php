<?php

namespace App\Livewire\Gdn;

use App\Models\Gdn;
use App\Models\GdnItem;
use App\Models\GrnItem;
use Livewire\Component;

class GdnCreate extends Component
{
    public $gdn_date;
    public $remarks;
    public $selectedItems = []; // Array of GrnItem IDs

    public function mount()
    {
        $this->authorize('create', Gdn::class);
        $this->gdn_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->authorize('create', Gdn::class);
        $this->validate([
            'gdn_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
            'selectedItems' => 'required|array|min:1'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () {
            $gdn = Gdn::create([
                'user_id' => auth()->id(),
                'quartz_id' => auth()->user()->quartz_id,
                'gdn_date' => $this->gdn_date,
                'remarks' => $this->remarks
            ]);

            foreach ($this->selectedItems as $grnItemId) {
                $grnItem = GrnItem::find($grnItemId);
                if ($grnItem) {
                    GdnItem::create([
                        'gdn_id' => $gdn->id,
                        'grn_item_id' => $grnItem->id,
                        'quantity' => $grnItem->quantity // Defaulting to full quantity
                    ]);
                }
            }
        });

        session()->flash('message', 'Goods Despatch Note created successfully.');
        return $this->redirect(route('gdns.index'), navigate: true);
    }

    public function render()
    {
        $this->authorize('create', Gdn::class);
        // Fetch items from confirmed GRNs that belong to this quartz
        $availableItems = GrnItem::whereHas('grnSession', function ($q) {
            $q->where('status', 'confirmed')
                ->where('quartz_id', auth()->user()->quartz_id);
        })
            ->with(['item', 'grnSession.shop'])
            ->latest()
            ->get();

        return view('livewire.gdn.gdn-create', [
            'availableItems' => $availableItems
        ]);
    }
}
