<?php

namespace App\Livewire\Grn;

use Livewire\Component;

class GrnCreate extends Component
{
    use \Livewire\WithFileUploads;

    public $quartz_id;
    public $shop_id;
    public $session_date;
    public $bill_images = [];
    public $items = [];
    public $selected_participants = [];

    // Preload info references
    public $allQuartzs;
    public $allShops;
    public $allItems;
    public $quartzUsers = [];

    public function mount()
    {
        $this->session_date = now()->format('Y-m-d\TH:i');
        $this->allQuartzs = \App\Models\Quartz::all();
        $this->allShops = \App\Models\Shop::all();
        $this->allItems = \App\Models\Item::all();

        // Auto-select Quartz for logged in user if available
        if (auth()->user()->quartz_id) {
            $this->quartz_id = auth()->user()->quartz_id;
            $this->loadQuartzUsers();
        }

        $this->addItem(); // Start with one empty item row
    }

    public function updatedQuartzId()
    {
        $this->loadQuartzUsers();
    }

    public function loadQuartzUsers()
    {
        if ($this->quartz_id) {
            $this->quartzUsers = \App\Models\User::where('quartz_id', $this->quartz_id)->get();
            // Default select all? Or Logged in User? Let's select all active logic later. For now empty or select all.
            // Let's select all by default as requested "check boxes of all users... to check how many of them to divide"
            $this->selected_participants = $this->quartzUsers->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->quartzUsers = [];
            $this->selected_participants = [];
        }
    }

    public function addItem()
    {
        $this->items[] = [
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

    // When Shop changes, maybe we should re-fetch prices? 
    // For now, let's keep it simple: users usually select Shop first.
    public function updatedShopId()
    {
        // Optional: Re-fetch prices for all existing items if shop changes
        foreach ($this->items as $index => $item) {
            if ($item['item_id']) {
                $this->fetchPrice($index, $item['item_id']);
            }
        }
    }

    // Recalculate total when quantity, price, or Item updates
    public function updatedItems($value, $key)
    {
        // $key looks like "0.quantity" or "0.item_id"
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
        } else {
            $this->items[$index]['unit_price'] = 0; // Reset or keep previous? Resetting is safer to indicate no price found.
        }
    }

    public function save()
    {
        $this->validate([
            'quartz_id' => 'required|exists:quartzs,id',
            'shop_id' => 'required|exists:shops,id',
            'session_date' => 'required|date',
            'bill_images.*' => 'image|max:10240', // 10MB max
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () {
            // Determine Status
            $status = count($this->bill_images) > 0 ? 'confirmed' : 'pending';

            // Create Session
            $session = \App\Models\GrnSession::create([
                'user_id' => auth()->id(),
                'quartz_id' => $this->quartz_id,
                'shop_id' => $this->shop_id,
                'session_date' => $this->session_date,
                'status' => $status,
                'confirmed_by' => null, // Reset auto-confirm logic? Let's keep manual confirmation for splitting clarity OR handle split here if confirmed.
                // If we confirm here, we MUST process split.
                // Let's set status to pending ALWAYS if we want them to review splits?
                // OR if images present, we auto-confirm AND split.
                // Let's stick to: Images = Confirmed. But we need to Process Split.
                // For simplicity now: If images present -> Confirmed, AND we run processConfirmation immediately.
                'confirmed_at' => null,
            ]);

            // Save Participants
            $session->participants()->attach($this->selected_participants);

            // Save Images
            foreach ($this->bill_images as $image) {
                $path = $image->store('grn-bills', 'public');
                $session->images()->create(['image_path' => $path]);
            }

            // Save Items
            foreach ($this->items as $itemData) {
                $session->grnItems()->create([
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['total_price'],
                ]);
            }

            if ($status === 'confirmed') {
                $session->update([
                    'confirmed_by' => auth()->id(),
                    'confirmed_at' => now(),
                ]);
                $session->processConfirmation(auth()->id());
            }
        });

        session()->flash('message', 'GRN Session created successfully.');
        return redirect()->route('grns.index');
    }

    public function render()
    {
        return view('livewire.grn.grn-create');
    }
}
