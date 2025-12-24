<?php

namespace App\Livewire\Unit;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showDeleteModal = false;
    public $unitIdToDelete = null;

    protected $listeners = [
        'unit-created' => '$refresh',
        'unit-updated' => '$refresh',
        'unit-deleted' => '$refresh'
    ];

    public function confirmDelete($id)
    {
        $this->unitIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Unit::find($this->unitIdToDelete)->delete();
        $this->showDeleteModal = false;
        $this->dispatch('unit-deleted');
    }

    public function render()
    {
        $units = Unit::withCount('itemPrices')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.unit.unit-index', compact('units'));
    }
}