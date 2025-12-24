<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdnItem extends Model
{
     protected $fillable = ['gdn_id', 'item_id', 'quantity', 'unit_price', 'total_price'];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
