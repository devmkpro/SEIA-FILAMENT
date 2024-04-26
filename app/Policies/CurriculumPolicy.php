<?php

namespace App\Policies;

use App\Models\Curriculum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CurriculumPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any Curriculum');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Curriculum $curriculum): bool
    {
        return $user->hasPermissionForSchool('view Curriculum', $curriculum->school->code);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Curriculum');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Curriculum $curriculum): bool
    {
        return $user->hasPermissionForSchool('update Curriculum', $curriculum->school->code);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Curriculum $curriculum): bool
    {
        return $user->hasPermissionForSchool('delete Curriculum', $curriculum->school->code);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Curriculum $curriculum): bool
    {
        return $user->hasPermissionForSchool('restore Curriculum', $curriculum->school->code);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Curriculum $curriculum): bool
    {
        return $user->hasPermissionForSchool('force-delete Curriculum', $curriculum->school->code);
    }
}
