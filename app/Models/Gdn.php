<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gdn extends Model
{
    protected $fillable = ['user_id', 'quartz_id', 'gdn_date', 'remarks'];

    protected $casts = [
        'gdn_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gdnItems()
    {
        return $this->hasMany(GdnItem::class);
    }
}
