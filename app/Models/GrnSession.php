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
