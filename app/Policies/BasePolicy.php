<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * The permission prefix for this policy (e.g., 'user', 'role', 'grn').
     *
     * @var string
     */
    protected $feature;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission("view-any-{$this->feature}") || $user->hasPermission("manage-{$this->feature}");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $model)
    {
        return $user->hasPermission("view-{$this->feature}") || $user->hasPermission("manage-{$this->feature}");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermission("create-{$this->feature}") || $user->hasPermission("manage-{$this->feature}");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $model)
    {
        return $user->hasPermission("update-{$this->feature}") || $user->hasPermission("manage-{$this->feature}");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $model)
    {
        return $user->hasPermission("delete-{$this->feature}") || $user->hasPermission("manage-{$this->feature}");
    }
}
