<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Services\GameService;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // List player games with stats
    public function listPlayerGamesWithStats(Request $request, $id, GameService $gameService)
    {
        $player = User::find($id);

        // Check if the user has the permission to view players
        if (Auth::user()->cannot('listPlayerGamesWithStats', $player)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $games = Game::where('user_id', $id)->select('dice_1', 'dice_2')->get();
        foreach ($games as $game) {
            $sum = $game->dice_1 + $game->dice_2;
            $game->result = $sum === 7 ? 'won' : 'lost';
        }
        $player_won_percentage = $gameService->getPercentageOfGamesWonByUser($player);

        return response()->json([
            'data' => $games,
            'player_won_percentage' => round($player_won_percentage, 2),
        ]);
    }

    // Play
    public function play(Request $request, $id)
    {
        $player = User::find($id);
        if (Auth::user()->cannot('play', $player)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $dice_1 = rand(1, 6);
        $dice_2 = rand(1, 6);

        $this->store($id, $dice_1, $dice_2);

        return response()->json([
            'dice_1' => $dice_1,
            'dice_2' => $dice_2,
        ]);
    }

    public function store($user_id, $dice_1, $dice_2)
    {
        Game::create([
            'user_id' => $user_id,
            'dice_1' => $dice_1,
            'dice_2' => $dice_2
        ]);
    }

    // Delete player games
    public function deletePlayerGames(Request $request, $id)
    {
        $player = User::find($id);

        // Check if the user has the permission to delete players
        if (Auth::user()->cannot('checkIsSameAsUserId', $player)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Delete
        $player->games()->delete();

        return response()->json([
            'message' => 'Player games deleted successfully'
        ]);
    }
}
