<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdnItem extends Model
{
    protected $fillable = ['gdn_id', 'grn_item_id', 'quantity'];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function grnItem()
    {
        return $this->belongsTo(GrnItem::class);
    }
}
