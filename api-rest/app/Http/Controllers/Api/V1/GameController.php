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
    /**
     * @OA\Get(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="List player games with stats",
     *     description="Retrieve a list of games played by the player along with statistics.",
     *     operationId="listPlayerGamesWithStats",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of games and player's win percentage",
     *         @OA\JsonContent(ref="#/components/schemas/ListPlayerGamesWithStats")
     *         
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */

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

        // Return games and player's win percentage
        return response()->json([
            'data' => $games,
            'player_won_percentage' => $player_won_percentage
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="Play a game",
     *     description="Rolls two dice for the player.",
     *     operationId="play",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dice roll results",
     *         @OA\JsonContent(
     *         @OA\Property(property="data", type="object",
     *             @OA\Property(property="dice_1", type="integer", example=3),
     *             @OA\Property(property="dice_2", type="integer", example=4)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */

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
            'data' => [
                'dice_1' => $dice_1,
                'dice_2' => $dice_2,
            ]
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

    /**
     * @OA\Delete(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="Delete player games",
     *     description="Deletes all games for a player.",
     *     operationId="deletePlayerGames",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player games deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player games deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */

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
