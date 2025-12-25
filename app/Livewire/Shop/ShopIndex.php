<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use Livewire\Component;
use Livewire\WithPagination;

class ShopIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $shopIdToDelete = null;

    protected $listeners = [
        'shop-created' => '$refresh',
        'shop-updated' => '$refresh',
        'shop-deleted' => '$refresh'
    ];

    public function confirmDelete($id)
    {
        $this->shopIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $shop = Shop::findOrFail($this->shopIdToDelete);
        $this->authorize('delete', $shop);
        $shop->delete();
        $this->showDeleteModal = false;
        $this->dispatch('shop-deleted');
    }

    public function render()
    {
        $this->authorize('viewAny', Shop::class);
        $shops = Shop::withCount('itemPrices')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.shop.shop-index', compact('shops'));
    }
}
