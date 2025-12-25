<?php

namespace App\Policies;

use App\Models\User;

class BankTransactionPolicy extends BasePolicy
{
    protected $feature = 'bank-transactions';
    public function process(User $user, $model)
    {
        return $user->hasPermission('process-bank-transactions') || $user->hasPermission('manage-bank-transactions');
    }
}
