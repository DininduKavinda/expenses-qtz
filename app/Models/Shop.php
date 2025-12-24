<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
      protected $fillable = ['name', 'location'];

    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }
}
