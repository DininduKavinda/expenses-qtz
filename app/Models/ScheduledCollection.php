<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledCollection extends Model
{
    protected $fillable = ['bank_account_id', 'amount', 'collection_date'];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}
