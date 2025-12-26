<?php

namespace App\Policies;

use App\Models\User;

class GdnPolicy extends BasePolicy
{
    protected $feature = 'gdns';

    public function viewAny(User $user)
    {
        if ($user->quartz_id !== null) {
            return true;
        }
        return parent::viewAny($user);
    }

    public function create(User $user)
    {
        if ($user->quartz_id !== null) {
            return true;
        }
        return parent::create($user);
    }

    public function view(User $user, $model)
    {
        if ($user->quartz_id !== null && $user->quartz_id === $model->quartz_id) {
            return true;
        }
        return parent::view($user, $model);
    }
}
