<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Members;
use App\Models\User;

class MembersPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Members');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('view Members');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Members');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('update Members');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('delete Members');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Members');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('restore Members');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Members');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('replicate Members');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Members');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Members $members): bool
    {
        return $user->checkPermissionTo('force-delete Members');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Members');
    }
}
