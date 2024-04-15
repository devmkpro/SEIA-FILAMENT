<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;

class StatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any State');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, State $state): bool
    {
        return $user->hasPermissionTo('view State');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create State');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, State $state): bool
    {
        return $user->hasPermissionTo('update State');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, State $state): bool
    {
        return $user->hasPermissionTo('delete State');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, State $state): bool
    {
        return $user->hasPermissionTo('restore State');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, State $state): bool
    {
        return $user->hasPermissionTo('force-delete State');
    }
}
