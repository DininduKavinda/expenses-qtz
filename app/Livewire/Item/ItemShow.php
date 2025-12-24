<?php

namespace App\Livewire\Item;

use App\Models\Item;
use Livewire\Component;

class ItemShow extends Component
{
    public $item_id;
    public $item;
    public $activeTab = 'details';

    public function mount($id)
    {
        $this->item_id = $id;
        $this->item = Item::with([
            'category', 
            'brand', 
            'unit',
            'prices' => function($query) {
                $query->with(['shop', 'unit'])
                      ->orderBy('date', 'desc')
                      ->orderBy('shop_id');
            }
        ])->findOrFail($id);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.item.item-show');
    }
}