<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Services\GameService;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Index
    public function listAllPlayersWithStats(Request $request, GameService $gameService)
    {
        //Check if the user has the permission to view players
        if (auth()->user()->cannot('listAllPlayersWithStats', User::class)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $playersWithStats = $gameService->getPlayersWithStats();

        // Calculate average percentage of games won
        $averagePercentageOfGamesWon = round($playersWithStats->avg('games_won_percentage'), 2);

        // Return players with games_won_percentage
        return response()->json([
            'data' => $playersWithStats,
            'average_percentage_of_games_won' => $averagePercentageOfGamesWon,
        ], Response::HTTP_OK);
    }

    // Update
    public function changePlayerNickname(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'nickname' => 'required|string|max:255',
        ]);

        // Get user model from id
        $user = User::find($id);

        // Check if the user has the permission to update the player
        if ($request->user()->cannot('changePlayerNickname', $user)) {
            abort(Response::HTTP_FORBIDDEN);
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
