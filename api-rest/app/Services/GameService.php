<?php

namespace App\Services;
use App\Models\User;
use App\Models\Game;
use Spatie\Permission\Models\Role;


class GameService
{

    public function getPlayers(){

        $playerRole = Role::where('name', 'player')->first();
        $players = $playerRole->users()->get();
        return $players;
    }

    public function getPlayersWithStats()
    {
        // Add games_won_percentage to each player
        $players = $this->getPlayers();
        $playersWithStats = $players->map(function ($player) {
            $player->games_won_percentage = $this->getPercentageOfGamesWonByUser($player);
            unset($player->pivot); // We don't want to include the pivot table in the response
            return $player;
        });

        return $playersWithStats;
    }

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