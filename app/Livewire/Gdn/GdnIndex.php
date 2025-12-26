<?php

namespace App\Livewire\Gdn;

use Livewire\Component;

class GdnIndex extends Component
{
    use \Livewire\WithPagination;

    public $dateFrom;
    public $dateTo;
    public $searchQuery;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'searchQuery' => ['except' => ''],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateFrom', 'dateTo', 'searchQuery'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $this->authorize('viewAny', \App\Models\Gdn::class);

        $query = \App\Models\Gdn::where('quartz_id', auth()->user()->quartz_id)
            ->with(['user', 'gdnItems.grnItem.item', 'gdnItems.grnItem.grnSession.shop']);

        if ($this->dateFrom) {
            $query->whereDate('gdn_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('gdn_date', '<=', $this->dateTo);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', '%' . $this->searchQuery . '%');
                })->orWhere('remarks', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $gdns = $query->latest()->paginate(10);

        return view('livewire.gdn.gdn-index', [
            'gdns' => $gdns
        ]);
    }
}
