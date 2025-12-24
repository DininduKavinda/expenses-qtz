<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = ['bank_account_id', 'user_id', 'amount', 'transaction_date', 'description', 'type'];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
