<?php

namespace App\Policies;

class GrnSessionPolicy extends BasePolicy
{
    protected $feature = 'grn-sessions';

    public function confirm($user, $model)
    {
        return $user->hasPermission('confirm-grn-sessions') || $user->hasPermission('manage-grn-sessions');
    }
}
