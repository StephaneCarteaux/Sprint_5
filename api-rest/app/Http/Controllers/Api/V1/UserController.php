<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use App\Services\GameService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // List all players with stats
    public function listAllPlayersWithStats(Request $request, GameService $gameService)
    {
        //Check if the user has the permission to view players
        if (Auth::user()->cannot('listAllPlayersWithStats', User::class)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_FORBIDDEN);
        }

        // Get players with games_won_percentage
        $playersWithStats = $gameService->getPlayersWithStats();

        // Get average_percentage_of_games_won
        $averagePercentageOfGamesWon = $gameService->getAveragePercentageOfGamesWon();

        // Return players with games_won_percentage
        return response()->json([
            'data' => $playersWithStats,
            'average_percentage_of_games_won' => $averagePercentageOfGamesWon,
        ], Response::HTTP_OK);
    }

    // Change player nickname
    public function changePlayerNickname(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'nickname' => 'required|string|max:255',
        ]);

        // Get user model from id
        $user = User::find($id);

        // Check if the user has the permission to update the player
        if (Auth::user()->cannot('checkIsSameAsUserId', $user)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_FORBIDDEN);
        }

        // Update player
        $user->nickname = $request->nickname;
        $user->save();

        // Return updated player
        return response()->json([
            'data' => $user
        ], Response::HTTP_OK);
    }
}
