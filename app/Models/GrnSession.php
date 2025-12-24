<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnSession extends Model
{
    protected $fillable = [
        'user_id',
        'quartz_id',
        'shop_id',
        'session_date',
        'status',
        'confirmed_by',
        'confirmed_at'
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'grn_participants', 'grn_session_id', 'user_id')->withTimestamps();
    }

    // Main business logic for processing a confirmed GRN
    // Main business logic for processing a confirmed GRN
    public function processConfirmation($confirmingUserId)
    {
        // Safety check: Don't double charge. Check if expense splits already exist?
        // Or if confirmed_at was just set.
        // If we call this immediately after setting status, the status check prevents it.
        // Let's modify to: if expense splits exist, return.

        if (\App\Models\ExpenseSplit::whereHas('grnItem', function ($q) {
            $q->where('grn_session_id', $this->id);
        })->exists()) {
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($confirmingUserId) {
            // Ensure status is confirmed
            if ($this->status !== 'confirmed') {
                $this->update([
                    'status' => 'confirmed',
                    'confirmed_by' => $confirmingUserId,
                    'confirmed_at' => now(),
                ]);
            }

            // Splitting Logic
            $participants = $this->participants;
            $count = $participants->count();

            if ($count > 0) {
                // Calculate total cost
                $totalCost = $this->grnItems()->sum('total_price');
                $splitAmount = $totalCost / $count;

                foreach ($participants as $user) {
                    // Record valid expense split
                    // Note: ExpenseSplit schema might need 'grn_session_id' or we iterate items?
                    // Schema: `grn_item_id`, `user_id`, `amount`.
                    // So we must split PER ITEM to keep data granular and correct.

                    foreach ($this->grnItems as $item) {
                        $itemSplit = $item->total_price / $count;
                        \App\Models\ExpenseSplit::create([
                            'grn_item_id' => $item->id,
                            'user_id' => $user->id,
                            'amount' => $itemSplit
                        ]);
                    }

                    // Update User Balance (Debt)
                    // Ensure UserAccount exists
                    $account = \App\Models\UserAccount::firstOrCreate(
                        ['user_id' => $user->id],
                        ['balance' => 0]
                    );

                    $account->increment('balance', $splitAmount);
                }
            }
        });
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function grnItems()
    {
        return $this->hasMany(GrnItem::class);
    }

    public function images()
    {
        return $this->hasMany(GrnImage::class);
    }

    public function needsConfirmation(): bool
    {
        return $this->images()->count() === 0;
    }
    public function quartz()
    {
        return $this->belongsTo(Quartz::class);
    }
}
