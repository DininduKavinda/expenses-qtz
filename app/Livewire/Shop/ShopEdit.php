<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use Livewire\Component;

class ShopEdit extends Component
{
    public $shop_id;
    public $shop;
    public $name = '';
    public $location = '';
    public $processing = false;
    public $showDeleteModal = false;

    protected $rules = [
        'name' => 'required|min:2|max:100|unique:shops,name,' . ',id',
        'location' => 'nullable|max:200',
    ];

    public function mount($id)
    {
        $this->shop_id = $id;
        $this->shop = Shop::findOrFail($id);
        $this->authorize('update', $this->shop);

        $this->name = $this->shop->name;
        $this->location = $this->shop->location;
    }

    public function update()
    {
        $this->validate();

        $this->processing = true;

        $this->shop->update([
            'name' => $this->name,
            'location' => $this->location,
        ]);

        $this->processing = false;
        $this->dispatch('shop-updated');
        $this->redirect(route('shops.index'), navigate: true);
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->authorize('delete', $this->shop);
        $this->shop->delete();
        $this->showDeleteModal = false;
        $this->dispatch('shop-deleted');
        $this->redirect(route('shops.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.shop.shop-edit');
    }
}
