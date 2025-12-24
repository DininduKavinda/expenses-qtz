<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = ['scheduled_collection_id', 'user_id', 'amount'];

    public function scheduledCollection()
    {
        return $this->belongsTo(ScheduledCollection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
