<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnIndex extends Component
{
    use \Livewire\WithPagination;

    public $dateFrom;
    public $dateTo;
    public $selectedShop;
    public $selectedStatus;
    public $searchQuery;
    public $showDeleteModal = false;
    public $grnIdToDelete = null;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'selectedShop' => ['except' => ''],
        'selectedStatus' => ['except' => ''],
        'searchQuery' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateFrom', 'dateTo', 'selectedShop', 'selectedStatus', 'searchQuery'])) {
            $this->resetPage();
        }
    }

    public function confirmDelete($id)
    {
        $this->grnIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete($id)
    {
        $grn = \App\Models\GrnSession::findOrFail($id);
        $this->authorize('delete', $grn);

        $grn->delete();

        $this->showDeleteModal = false;
        $this->grnIdToDelete = null;

        session()->flash('message', 'GRN session deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', \App\Models\GrnSession::class);

        $isAdmin = auth()->user()->role && auth()->user()->role->slug === 'admin';

        $query = \App\Models\GrnSession::with(['quartz', 'shop', 'user', 'confirmedBy']);

        if (!$isAdmin) {
            $query->where('quartz_id', auth()->user()->quartz_id);
        }

        if ($this->dateFrom) {
            $query->whereDate('session_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('session_date', '<=', $this->dateTo);
        }

        if ($this->selectedShop) {
            $query->where('shop_id', $this->selectedShop);
        }

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->whereHas('shop', function ($sq) {
                    $sq->where('name', 'like', '%' . $this->searchQuery . '%');
                })->orWhereHas('user', function ($uq) {
                    $uq->where('name', 'like', '%' . $this->searchQuery . '%');
                });
            });
        }

        $grns = $query->latest()->paginate(10);

        return view('livewire.grn.grn-index', [
            'grns' => $grns,
            'shops' => \App\Models\Shop::all(),
        ]);
    }
}
