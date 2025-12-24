<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
     protected $fillable = ['grn_session_id', 'item_id', 'quantity', 'unit_price', 'total_price'];

    public function grnSession()
    {
        return $this->belongsTo(GrnSession::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
