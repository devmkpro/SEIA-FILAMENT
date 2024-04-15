<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSchool;
use Illuminate\Auth\Access\Response;

class UserSchoolPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any UserSchool');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserSchool $userSchool): bool
    {
        return $user->hasPermissionTo('view UserSchool');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create UserSchool');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserSchool $userSchool): bool
    {
        return $user->hasPermissionTo('update UserSchool');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserSchool $userSchool): bool
    {
        return $user->hasPermissionTo('delete UserSchool');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserSchool $userSchool): bool
    {
        return $user->hasPermissionTo('restore UserSchool');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserSchool $userSchool): bool
    {
        return $user->hasPermissionTo('force-delete UserSchool');
    }
}
