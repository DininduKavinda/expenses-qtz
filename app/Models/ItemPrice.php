<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
     protected $fillable = ['item_id', 'shop_id', 'unit_id', 'price', 'date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
