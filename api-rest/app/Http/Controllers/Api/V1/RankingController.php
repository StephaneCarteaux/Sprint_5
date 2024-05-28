<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use App\Services\GameService;
use Spatie\Permission\Models\Role;

class RankingController extends Controller
{
    public function getRanking(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();

        // Sort players by games_won_percentage
        $ranking = $playersWithStats->sortByDesc('games_won_percentage');

        // Calculate average percentage of games won
        $averagePercentageOfGamesWon = round($playersWithStats->avg('games_won_percentage'), 2);

        // Return players with games_won_percentage
        return response()->json([
            'data' => $ranking,
            'average_percentage_of_games_won' => $averagePercentageOfGamesWon,
        ], Response::HTTP_OK);
    }
}
