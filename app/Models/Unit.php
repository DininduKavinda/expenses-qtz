<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
     protected $fillable = ['name', 'description'];

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }
}
