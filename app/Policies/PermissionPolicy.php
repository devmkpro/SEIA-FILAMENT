<?php

namespace App\Policies;

use App\Models\Permission as PermissionModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PermissionModel $permission): bool
    {
        return $user->hasPermissionTo('view Permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PermissionModel $permission): bool
    {
        return $user->hasPermissionTo('update Permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PermissionModel $permission): bool
    {
        return $user->hasPermissionTo('delete Permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PermissionModel $permission): bool
    {
        return $user->hasPermissionTo('restore Permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PermissionModel $permission): bool
    {
        return $user->hasPermissionTo('force-delete Permission');
    }
}
