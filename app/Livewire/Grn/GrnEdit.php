<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnEdit extends Component
{
    use \Livewire\WithFileUploads;

    public \App\Models\GrnSession $grn;

    public $quartz_id;
    public $shop_id;
    public $session_date;
    public $bill_images = []; // New uploads
    public $existing_images = []; // Existing images
    public $items = [];

    // Preload info references
    public $allQuartzs;
    public $allShops;
    public $allItems;

    public function mount(\App\Models\GrnSession $grn)
    {
        $this->grn = $grn->load(['grnItems', 'images']);
        $this->quartz_id = $grn->quartz_id;
        $this->shop_id = $grn->shop_id;
        $this->session_date = $grn->session_date->format('Y-m-d\TH:i');
        $this->existing_images = $grn->images;

        foreach ($grn->grnItems as $item) {
            $this->items[] = [
                'id' => $item->id, // Track existing items
                'item_id' => $item->item_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ];
        }

        $this->allQuartzs = \App\Models\Quartz::all();
        $this->allShops = \App\Models\Shop::all();
        $this->allItems = \App\Models\Item::all();
    }

    public function addItem()
    {
        $this->items[] = [
            'id' => null, // New item
            'item_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total_price' => 0
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedShopId()
    {
        foreach ($this->items as $index => $item) {
            if ($item['item_id']) {
                $this->fetchPrice($index, $item['item_id']);
            }
        }
    }

    public function removeExistingImage($imageId)
    {
        \App\Models\GrnImage::find($imageId)->delete();
        $this->existing_images = $this->existing_images->reject(fn($img) => $img->id === $imageId);
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) == 2) {
            $index = $parts[0];
            $field = $parts[1];

            if ($field === 'item_id') {
                $this->fetchPrice($index, $value);
            }

            if (in_array($field, ['quantity', 'unit_price', 'item_id'])) {
                $qty = floatval($this->items[$index]['quantity']);
                $price = floatval($this->items[$index]['unit_price']);
                $this->items[$index]['total_price'] = $qty * $price;
            }
        }
    }

    public function fetchPrice($index, $itemId)
    {
        if (!$this->shop_id || !$itemId) return;

        $priceRecord = \App\Models\ItemPrice::where('item_id', $itemId)
            ->where('shop_id', $this->shop_id)
            ->latest('date')
            ->first();

        if ($priceRecord) {
            $this->items[$index]['unit_price'] = $priceRecord->price;
        }
    }

    public function save()
    {
        $this->validate([
            'quartz_id' => 'required|exists:quartzs,id',
            'shop_id' => 'required|exists:shops,id',
            'session_date' => 'required|date',
            'bill_images.*' => 'nullable|image|max:10240',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () {

            // save images first to check count for status
            foreach ($this->bill_images as $image) {
                $path = $image->store('grn-bills', 'public');
                $this->grn->images()->create(['image_path' => $path]);
            }

            // Refresh images count
            $imageCount = $this->grn->images()->count();

            // Determine Status Logic: 
            // If previously pending and now has images -> confirmed? 
            // Or just stick to: if images > 0 -> confirmed/pending logic.
            // Let's safe-guard: if confirmed, stay confirmed. If pending, check images.
            $status = $this->grn->status;
            if ($status !== 'confirmed' && ($imageCount > 0)) {
                $status = 'confirmed';
                $this->grn->confirmed_by = auth()->id();
                $this->grn->confirmed_at = now();
            }

            $this->grn->update([
                'quartz_id' => $this->quartz_id,
                'shop_id' => $this->shop_id,
                'session_date' => $this->session_date,
                'status' => $status
            ]);

            // Sync Items: Delete all and recreate is easiest for this complexity, 
            // or smart sync. Let's delete removed ones and update/create.

            // Actually, simplest strategy for now with limited time:
            // 1. Delete all existing items for this session
            $this->grn->grnItems()->delete();

            // 2. Create all from form
            foreach ($this->items as $itemData) {
                $this->grn->grnItems()->create([
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                ]);
            }
        });

        session()->flash('message', 'GRN Session updated successfully.');
        return redirect()->route('grns.index');
    }

    public function render()
    {
        return view('livewire.grn.grn-edit');
    }
}
