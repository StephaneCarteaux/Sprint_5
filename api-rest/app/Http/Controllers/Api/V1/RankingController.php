<?php

namespace App\Http\Controllers\Api\V1;

use OpenApi\Annotations as OA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Services\GameService;

class RankingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/players/ranking",
     *     tags={"Ranking"},
     *     summary="Get ranking",
     *     description="Retrieve the ranking of players sorted by their win percentage.",
     *     operationId="getRanking",
     *     @OA\Response(
     *         response=200,
     *         description="List of players with their win percentage",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="nickname", type="string", example="player1"),
     *                 @OA\Property(property="games_won_percentage", type="number", format="float", example=75.0)
     *             ))
     *         )
     *     )
     * )
     */

    // Get ranking
    public function getRanking(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();
        $ranking = $gameService->getRanking($playersWithStats);

        // Return players with games_won_percentage
        return response()->json([
            'data' => $ranking,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/players/ranking/loser",
     *     tags={"Ranking"},
     *     summary="Get loser",
     *     description="Retrieve the player with the lowest win percentage.",
     *     operationId="getLoser",
     *     @OA\Response(
     *         response=200,
     *         description="Player with the lowest win percentage",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="nickname", type="string", example="player1"),
     *                 @OA\Property(property="games_won_percentage", type="number", format="float", example=25.0)
     *             )
     *         )
     *     )
     * )
     */

    // Get loser
    public function getLoser(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();
        $loser = $gameService->getLoser($playersWithStats);
        return response()->json([
            'data' => $loser
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/players/ranking/winner",
     *     tags={"Ranking"},
     *     summary="Get winner",
     *     description="Retrieve the player with the highest win percentage.",
     *     operationId="getWinner",
     *     @OA\Response(
     *         response=200,
     *         description="Player with the highest win percentage",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="nickname", type="string", example="player1"),
     *                 @OA\Property(property="games_won_percentage", type="number", format="float", example=85.0)
     *             )
     *         )
     *     )
     * )
     */

    // Get winner
    public function getWinner(Request $request, GameService $gameService)
    {
        $playersWithStats = $gameService->getPlayersWithStats();
        $winner = $gameService->getWinner($playersWithStats);
        return response()->json([
            'data' => $winner
        ], Response::HTTP_OK);
    }
}
