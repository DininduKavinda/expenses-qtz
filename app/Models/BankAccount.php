<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
     protected $fillable = ['quartz_id', 'name', 'balance'];

    public function quartz()
    {
        return $this->belongsTo(Quartz::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function scheduledCollections()
    {
        return $this->hasMany(ScheduledCollection::class);
    }
}
