<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BusinessPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Business $business): Response
    {
        return $user->id === $business->user_id
            ? Response::allow()
            : Response::deny("Access denied for business #$business->name");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Business $business): Response
    {
        return $user->id === $business->user_id
            ? Response::allow()
            : Response::deny("Access denied for business #$business->name");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Business $business): Response
    {
        return $user->id === $business->user_id
            ? Response::allow()
            : Response::deny("Access denied for business #$business->name");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Business $business): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Business $business): bool
    {
        return false;
    }
}
