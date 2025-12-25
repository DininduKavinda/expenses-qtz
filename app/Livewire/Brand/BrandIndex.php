<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class BrandIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $brandIdToDelete = null;

    protected $listeners = [
        'brand-created' => '$refresh',
        'brand-updated' => '$refresh',
        'brand-deleted' => '$refresh'
    ];

    public function confirmDelete($id)
    {
        $this->brandIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $brand = Brand::findOrFail($this->brandIdToDelete);
        $this->authorize('delete', $brand);
        $brand->delete();
        $this->showDeleteModal = false;
        $this->dispatch('brand-deleted');
    }

    public function render()
    {
        $this->authorize('viewAny', Brand::class);
        $brands = Brand::with('category') // Eager load category relationship
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.brand.brand-index', compact('brands'));
    }
}
