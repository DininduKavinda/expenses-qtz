<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use Livewire\Component;

class ShopCreate extends Component
{
    public function mount()
    {
        $this->authorize('create', Shop::class);
    }

    public $name = '';
    public $location = '';
    public $processing = false;

    protected $rules = [
        'name' => 'required|min:2|max:100|unique:shops,name',
        'location' => 'nullable|max:200',
    ];

    public function save()
    {
        $this->validate();

        $this->processing = true;

        Shop::create([
            'name' => $this->name,
            'location' => $this->location,
        ]);

        $this->processing = false;
        $this->dispatch('shop-created');
        $this->redirect(route('shops.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.shop.shop-create');
    }
}
