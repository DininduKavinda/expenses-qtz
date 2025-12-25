<?php

namespace App\Policies;

use App\Models\User;

class ExpenseSplitPolicy extends BasePolicy
{
    protected $feature = 'expense-splits';
    public function process(User $user, $model)
    {
        return $user->hasPermission('process-payments') || $user->hasPermission('process-expense-splits') || $user->hasPermission('manage-expense-splits');
    }
}
