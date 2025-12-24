<?php

namespace App\Livewire\Quartz;

use App\Models\Quartz;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class QuartzIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $showDeleteModal = false;
    public $quartzIdToDelete;

    protected $queryString = ['search', 'perPage', 'sortField', 'sortDirection'];

    public function mount()
    {
        // Check permission if needed
        // Gate::authorize('viewAny', Quartz::class);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->quartzIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $quartz = Quartz::findOrFail($this->quartzIdToDelete);
        
        // Check if quartz has users or bank accounts
        if ($quartz->users()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete quartz that has users assigned!'
            ]);
            $this->showDeleteModal = false;
            return;
        }

        if ($quartz->bankAccounts()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete quartz that has bank accounts!'
            ]);
            $this->showDeleteModal = false;
            return;
        }

        $quartz->delete();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Quartz deleted successfully!'
        ]);
        
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = Quartz::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $quartzes = $query->orderBy($this->sortField, $this->sortDirection)
                         ->paginate($this->perPage);

        return view('livewire.quartz.quartz-index', [
            'quartzes' => $quartzes
        ]);
    }
}