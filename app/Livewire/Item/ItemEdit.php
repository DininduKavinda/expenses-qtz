<?php

namespace App\Livewire\Item;

use App\Models\Item;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Shop;
use Livewire\Component;

class ItemEdit extends Component
{
    public $item_id;
    public $item;
    public $name = '';
    public $category_id = '';
    public $brand_id = '';
    public $unit_id = '';
    public $prices = [];
    public $processing = false;
    public $showDeleteModal = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
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

    public function mount($id)
    {
        $this->item_id = $id;
        $this->item = Item::with(['prices.shop', 'prices.unit'])->findOrFail($id);
        $this->authorize('update', $this->item);

        $this->name = $this->item->name;
        $this->category_id = $this->item->category_id;
        $this->brand_id = $this->item->brand_id;
        $this->unit_id = $this->item->unit_id;

        // Load existing prices
        $this->prices = $this->item->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'shop_id' => $price->shop_id,
                'unit_id' => $price->unit_id,
                'price' => $price->price,
                'date' => $price->date,
            ];
        })->toArray();

        // If no prices exist, add one default entry
        if (empty($this->prices)) {
            $this->prices[] = [
                'shop_id' => '',
                'unit_id' => $this->unit_id,
                'price' => '',
                'date' => date('Y-m-d')
            ];
        }
    }

    public function addPrice()
    {
        $this->prices[] = [
            'id' => null,
            'shop_id' => '',
            'unit_id' => $this->unit_id,
            'price' => '',
            'date' => date('Y-m-d')
        ];
    }

    public function removePrice($index)
    {
        // If price has an ID, we need to delete it from database
        if (isset($this->prices[$index]['id'])) {
            $priceId = $this->prices[$index]['id'];
            $this->item->prices()->where('id', $priceId)->delete();
        }

        unset($this->prices[$index]);
        $this->prices = array_values($this->prices);
    }

    public function updatedUnitId($value)
    {
        // Update unit_id for all price entries when item unit changes
        foreach ($this->prices as $index => $price) {
            if (empty($price['unit_id'])) {
                $this->prices[$index]['unit_id'] = $value;
            }
        }
    }

    public function update()
    {
        $this->validate();

        $this->processing = true;

        // Update the item
        $this->item->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id ?: null,
            'unit_id' => $this->unit_id,
        ]);

        // Update or create prices
        foreach ($this->prices as $price) {
            if (!empty($price['shop_id']) && !empty($price['price'])) {
                if (isset($price['id'])) {
                    // Update existing price
                    $this->item->prices()->where('id', $price['id'])->update([
                        'shop_id' => $price['shop_id'],
                        'unit_id' => $price['unit_id'],
                        'price' => $price['price'],
                        'date' => $price['date'],
                    ]);
                } else {
                    // Create new price
                    $this->item->prices()->create([
                        'shop_id' => $price['shop_id'],
                        'unit_id' => $price['unit_id'],
                        'price' => $price['price'],
                        'date' => $price['date'],
                    ]);
                }
            }
        }

        $this->processing = false;

        session()->flash('message', 'Item updated successfully!');
        $this->dispatch('item-updated');
        $this->redirect(route('items.index'), navigate: true);
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->authorize('delete', $this->item);
        $this->item->delete();
        $this->showDeleteModal = false;
        $this->dispatch('item-deleted');
        $this->redirect(route('items.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $shops = Shop::orderBy('name')->get();

        return view('livewire.item.item-edit', compact('categories', 'brands', 'units', 'shops'));
    }
}
