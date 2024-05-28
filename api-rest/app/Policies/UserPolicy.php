<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function listAllPlayersWithStats(User $authenticatedUser): bool
    {
        // Check if the user has the permission to view players
        return $authenticatedUser->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function listPlayerGamesWithStats(User $authenticatedUser, User $model)
    {
        // Check if the user has the permission to view players
        return $authenticatedUser->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function changePlayerNickname(User $authenticatedUser, User $model): bool
    {
        return $authenticatedUser->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}