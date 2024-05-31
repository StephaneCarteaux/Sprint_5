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
    /**
     * @OA\Get(
     *     path="/players",
     *     tags={"Users"},
     *     summary="List all players with stats",
     *     description="Retrieve a list of all players with their game statistics and the average win percentage.",
     *     operationId="listAllPlayersWithStats",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of players with their win percentage and average win percentage",
     *         @OA\JsonContent(ref="#/components/schemas/ListAllPlayersWithStats"),
     * 
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * 
     * )
     */

    // List all players with stats
    public function listAllPlayersWithStats(Request $request, GameService $gameService)
    {
        //Check if the user has the permission to view players
        if (Auth::user()->cannot('listAllPlayersWithStats', User::class)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Get players with games_won_percentage
        $playersWithStats = $gameService->getPlayersWithStats();

        // Get average_percentage_of_games_won
        $averagePercentageOfGamesWon = $gameService->getAveragePercentageOfGamesWon();

        // Return players with games_won_percentage
        return response()->json([
            'data' => $playersWithStats,
            'average_percentage_of_games_won' => $averagePercentageOfGamesWon
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/players/{id}",
     *     tags={"Users"},
     *     summary="Change player nickname",
     *     description="Change the nickname of a player.",
     *     operationId="changePlayerNickname",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Player ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nickname", type="string", example="new_nickname")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nickname updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nickname updated successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="nickname", type="string", example="john_doe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */

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
            ], Response::HTTP_UNAUTHORIZED);
        }

        // If nickname is not unique, return error
        if ($request->nickname !== 'Anonim' && User::where('nickname', $request->nickname)->exists()) {
            return response()->json([
                'message' => 'The nickname has already been taken.',
                'errors' => ['nickname' => ['The nickname has already been taken.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update player
        $user->nickname = $request->nickname;
        $user->save();

        // Return updated player
        return response()->json([
            'message' => 'Nickname updated successfully',
            'data' => [
                'name' => $user->name,
                'nickname' => $user->nickname
            ]
        ], Response::HTTP_OK);
    }
}
