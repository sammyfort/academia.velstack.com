<?php

namespace App\Policies;

use App\Models\Signboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SignboardPolicy
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
    public function view(User $user, Signboard $signboard): Response
    {
        return $user->id == $signboard->service->user_id
            ? Response::allow()
            : Response::denyWithStatus(404, "Signboard not Found");
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
    public function update(User $user, Signboard $signboard): Response
    {
        return $user->id == $signboard->service->user_id
            ? Response::allow()
            : Response::denyWithStatus(404, "Signboard not Found");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Signboard $signboard): Response
    {
        return $user->id == $signboard->service->user_id
            ? Response::allow()
            : Response::denyWithStatus(404, "Signboard not Found");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Signboard $signboard): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Signboard $signboard): bool
    {
        return false;
    }
}
