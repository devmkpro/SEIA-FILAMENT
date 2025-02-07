<?php

namespace App\Policies;

use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any SchoolYear');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SchoolYear $schoolYear): bool
    {
        return $user->hasPermissionTo('view SchoolYear');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create SchoolYear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SchoolYear $schoolYear): bool
    {
        return $user->hasPermissionTo('update SchoolYear');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SchoolYear $schoolYear): bool
    {
        return $user->hasPermissionTo('delete SchoolYear');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SchoolYear $schoolYear): bool
    {
        return $user->hasPermissionTo('restore SchoolYear');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SchoolYear $schoolYear): bool
    {
        return $user->hasPermissionTo('force-delete SchoolYear');
    }
}
