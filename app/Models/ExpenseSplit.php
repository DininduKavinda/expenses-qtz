<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseSplit extends Model
{
     protected $fillable = ['grn_item_id', 'user_id', 'amount'];

    public function grnItem()
    {
        return $this->belongsTo(GrnItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
