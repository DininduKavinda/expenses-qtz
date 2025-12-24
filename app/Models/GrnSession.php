<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnSession extends Model
{
   protected $fillable = ['user_id', 'quartz_id', 'session_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grnItems()
    {
        return $this->hasMany(GrnItem::class);
    }

    public function images()
    {
        return $this->hasMany(GrnImage::class);
    }
}
