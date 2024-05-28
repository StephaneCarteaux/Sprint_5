<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Services\GameService;
use App\Models\User;
use Illuminate\Http\Response;

class GameController extends Controller
{
    // Get all players whith stats (Admin only)
    public function listPlayerGamesWithStats(Request $request, $id, GameService $gameService)
    {
        $player = User::find($id);

        // Check if the user has the permission to view players
        if (auth()->user()->cannot('listPlayerGamesWithStats', $player)) {
            abort(Response::HTTP_FORBIDDEN);
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

    // A player throws dices
    public function play(Request $request, $id)
    {
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
}
