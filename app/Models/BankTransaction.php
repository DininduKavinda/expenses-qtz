<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
      protected $fillable = ['bank_account_id', 'amount', 'transaction_date', 'description'];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
