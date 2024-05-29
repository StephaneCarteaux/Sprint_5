<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Chech authenticated user is same as model user
    public function checkIsSameAsUserId(User $authenticatedUser, User $model): bool
    {
        return $authenticatedUser->id === $model->id;
    }

    // List all players with stats
    public function listAllPlayersWithStats(User $authenticatedUser): bool
    {
        return $authenticatedUser->hasRole('admin');
    }

    // List player games with stats
    public function listPlayerGamesWithStats(User $authenticatedUser, User $model): bool
    {
        $isSameAsUserId = $this->checkIsSameAsUserId($authenticatedUser, $model);
        $isAdmin = $authenticatedUser->hasRole('admin');
        return $isSameAsUserId || $isAdmin;
    }

    // Play
    public function play(User $authenticatedUser, User $model): bool
    {
        $isSameAsUserId = $this->checkIsSameAsUserId($authenticatedUser, $model);
        $isPlayer = $authenticatedUser->hasRole('player');
        return $isSameAsUserId && $isPlayer;
    }
}
