<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = ['bank_account_id', 'user_id', 'amount', 'transaction_date', 'description', 'type', 'source_transaction_id'];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenseSplits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }

    public function sourceTransaction()
    {
        return $this->belongsTo(BankTransaction::class, 'source_transaction_id');
    }

    public function appliedPayments()
    {
        return $this->hasMany(BankTransaction::class, 'source_transaction_id');
    }
}
