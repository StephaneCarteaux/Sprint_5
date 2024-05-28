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

        // Get all players
        $playerRole = Role::where('name', 'player')->first();
        $players = $playerRole->users()->get();

        // Add games_won_percentage to each player
        $playersWithStats = $players->map(function ($player) use ($gameService) {
            $player->games_won_percentage = $gameService->getPercentageOfGamesWonByUser($player);
            unset($player->pivot); // We don't want to include the pivot table in the response
            return $player;
        });

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
