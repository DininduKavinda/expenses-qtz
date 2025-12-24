<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Shop;
use Livewire\Component;

class ItemCreate extends Component
{
    public $name = '';
    public $category_id = '';
    public $brand_id = '';
    public $unit_id = '';
    public $prices = [
        ['shop_id' => '', 'unit_id' => '', 'price' => '', 'date' => '']
    ];
    public $processing = false;

    protected $rules = [
        'name' => 'required|min:2|max:100|unique:items,name',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'unit_id' => 'required|exists:units,id',
        'prices.*.shop_id' => 'required|exists:shops,id',
        'prices.*.unit_id' => 'required|exists:units,id',
        'prices.*.price' => 'required|numeric|min:0|max:999999.99',
        'prices.*.date' => 'required|date',
    ];

    protected $messages = [
        'prices.*.shop_id.required' => 'Please select a shop',
        'prices.*.unit_id.required' => 'Please select a unit',
        'prices.*.price.required' => 'Price is required',
        'prices.*.price.numeric' => 'Price must be a number',
        'prices.*.price.min' => 'Price cannot be negative',
        'prices.*.price.max' => 'Price is too high',
        'prices.*.date.required' => 'Date is required',
        'prices.*.date.date' => 'Please enter a valid date',
    ];

    public function addPrice()
    {
        $this->prices[] = [
            'shop_id' => '', 
            'unit_id' => $this->unit_id, // Default to item's unit
            'price' => '', 
            'date' => date('Y-m-d')
        ];
    }

    public function removePrice($index)
    {
        unset($this->prices[$index]);
        $this->prices = array_values($this->prices);
    }

    public function updatedUnitId($value)
    {
        // Update unit_id for all price entries when item unit changes
        foreach ($this->prices as $index => $price) {
            $this->prices[$index]['unit_id'] = $value;
        }
    }

    public function save()
    {
        $this->validate();

        $this->processing = true;

        // Create the item
        $item = Item::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id ?: null,
            'unit_id' => $this->unit_id,
        ]);

        // Create prices
        foreach ($this->prices as $price) {
            if (!empty($price['shop_id']) && !empty($price['price'])) {
                $item->prices()->create([
                    'shop_id' => $price['shop_id'],
                    'unit_id' => $price['unit_id'],
                    'price' => $price['price'],
                    'date' => $price['date'],
                ]);
            }
        }

        $this->processing = false;
        
        session()->flash('message', 'Item created successfully!');
        $this->dispatch('item-created');
        $this->redirect(route('items.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $shops = Shop::orderBy('name')->get();

        return view('livewire.item.item-create', compact('categories', 'brands', 'units', 'shops'));
    }
}