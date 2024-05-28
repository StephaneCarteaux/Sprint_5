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
        $ranking = $playersWithStats->sortByDesc('games_won_percentage')->select('nickname', 'games_won_percentage')->values();

        // Return players with games_won_percentage
        return response()->json([
            'data' => $ranking,
        ], Response::HTTP_OK);
    }

    public function getLoser(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();
        $loser = $playersWithStats->sortByDesc('games_won_percentage')->select('nickname', 'games_won_percentage')->values()->first();
        return response()->json([
            'data' => $loser
        ], Response::HTTP_OK);
    }

    public function getWinner(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();
        $winner = $playersWithStats->sortBy('games_won_percentage')->select('nickname', 'games_won_percentage')->values()->first();
        return response()->json([
            'data' => $winner
        ], Response::HTTP_OK);
    }
}
