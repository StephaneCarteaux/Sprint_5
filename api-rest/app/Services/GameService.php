<?php

namespace App\Services;

use App\Models\User;
use App\Models\Game;
use Spatie\Permission\Models\Role;


class GameService
{
    // Get players
    public function getPlayers()
    {

        $playerRole = Role::where('name', 'player')->first();
        $players = $playerRole->users()->get();
        return $players;
    }

    // Get players with stats
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

    // Get ranking
    public function getRanking($playersWithStats)
    {
        // Sort players by games_won_percentage
        $ranking = $playersWithStats->sortBy([
            ['games_won_percentage', 'desc'],
            ['created_at', 'asc'],
        ])->select('nickname', 'games_won_percentage')->values();
        return $ranking;
    }

    // Get loser
    public function getLoser($playersWithStats)
    {
        $loser = $playersWithStats->sortBy([
            ['games_won_percentage', 'asc'],
            ['created_at', 'asc'],
        ])->select('nickname', 'games_won_percentage')->values()->first();

        return $loser;
    }

    // Get winner
    public function getWinner($playersWithStats)
    {
        $winner = $playersWithStats->sortBy([
            ['games_won_percentage', 'd'],
            ['created_at', 'asc'],
        ])->select('nickname', 'games_won_percentage')->values()->first();
        return $winner;
    }

    // Play
    public function play($player)
    {
        $dice_1 = rand(1, 6);
        $dice_2 = rand(1, 6);

        $data = [
            'player_id' => $player->id,
            'dice_1' => $dice_1,
            'dice_2' => $dice_2
        ];

        return $data;
    }

    // Get games
    public function getGamesWithResult(User $player)
    {
        $games = Game::where('user_id', $player->id)->select('dice_1', 'dice_2')->get();
        foreach ($games as $game) {
            $sum = $game->dice_1 + $game->dice_2;
            $game->result = $sum === 7 ? 'won' : 'lost';
        }

        return $games;
    }

    // Get percentage of games won by user
    public function getPercentageOfGamesWonByUser(User $player)
    {
        $totalGames = $player->games()->count();
        if ($totalGames === 0) {
            return 0;
        }
        $wonGames = $player->games()->whereRaw('dice_1 + dice_2 = 7')->count();
        return $wonGames / $totalGames * 100;
    }

    // Get average percentage of games won
    public function getAveragePercentageOfGamesWon()
    {
        return round($this->getPlayersWithStats()->avg('games_won_percentage'), 2);
    }
}
