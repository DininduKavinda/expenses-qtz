<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class ItemIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $unitFilter = '';
    public $showDeleteModal = false;
    public $itemIdToDelete = null;

    protected $listeners = [
        'item-created' => '$refresh',
        'item-updated' => '$refresh',
        'item-deleted' => '$refresh'
    ];

    public function confirmDelete($id)
    {
        $this->itemIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $item = Item::findOrFail($this->itemIdToDelete);
        $this->authorize('delete', $item);
        $item->delete();
        $this->showDeleteModal = false;
        $this->dispatch('item-deleted');
    }

    public function render()
    {
        $this->authorize('viewAny', Item::class);
        $items = Item::with(['category', 'brand', 'unit', 'prices'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->brandFilter, function ($query) {
                $query->where('brand_id', $this->brandFilter);
            })
            ->when($this->unitFilter, function ($query) {
                $query->where('unit_id', $this->unitFilter);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('livewire.item.item-index', compact('items', 'categories', 'brands', 'units'));
    }
}
