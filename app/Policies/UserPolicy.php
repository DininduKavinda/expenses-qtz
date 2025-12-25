<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    protected $feature = 'users';

    /**
     * Additional custom checks can go here
     */
    public function toggleActive(User $user, User $model)
    {
        return ($user->hasPermission('manage-users') || $user->hasPermission('update-users'))
            && $user->id !== $model->id;
    }
}
