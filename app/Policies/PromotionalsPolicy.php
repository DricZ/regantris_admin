<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Promotional;
use App\Models\User;

class PromotionalsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Promotional');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('view Promotional');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Promotional');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('update Promotional');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('delete Promotional');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Promotional');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('restore Promotional');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Promotional');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('replicate Promotional');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Promotional');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Promotional $promotional): bool
    {
        return $user->checkPermissionTo('force-delete Promotional');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Promotional');
    }
}
