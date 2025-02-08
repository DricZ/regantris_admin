<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

use App\Models\User;
use App\Models\RedeemLog;

class RedeemLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any RedeemLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('view RedeemLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create RedeemLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('update RedeemLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('delete RedeemLog');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any RedeemLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('restore RedeemLog');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any RedeemLog');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('replicate RedeemLog');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder RedeemLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RedeemLog $model): bool
    {
        return $user->checkPermissionTo('force-delete RedeemLog');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any RedeemLog');
    }
}