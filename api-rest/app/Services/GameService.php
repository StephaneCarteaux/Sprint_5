<?php

namespace App\Services;
use App\Models\User;
use App\Models\Game;

class GameService
{
    public function getPercentageOfGamesWonByUser(User $player)
    {
        $totalGames = $player->games()->count();
        if ($totalGames === 0) {
            return 0;
        }
        $wonGames = $player->games()->whereRaw('dice_1 + dice_2 = 7')->count();
        return $wonGames / $totalGames * 100;
    }
}