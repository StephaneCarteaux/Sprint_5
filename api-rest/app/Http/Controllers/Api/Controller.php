<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api Rest Stef - Documentation",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     in="header",
 *     bearerFormat="JWT",
 * )
 *
 * @OA\Tag(
 *     name="Login",
 * )
 * 
 * @OA\Tag(
 *     name="Register",
 * )
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="User related endpoints",
 * )
 * 
 * @OA\Tag(
 *     name="Games",
 *     description="Game related endpoints",
 * )
 * 
 * @OA\Tag(
 *     name="Ranking",
 *     description="Ranking related endpoints",
 * )
 *
 * @OA\Components(
 *     @OA\Schema(
 *         schema="Player",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="nickname", type="string", example="john"),
 *         @OA\Property(property="registered_at", type="string", format="date-time", example="2020-01-01T00:00:00.000000Z"),
 *         @OA\Property(property="email", type="string", format="email", example="jVHrj@example.com"),
 *         @OA\Property(property="email_verified_at", type="string", format="date-time", example="2020-01-01T00:00:00.000000Z"),
 *         @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-01T00:00:00.000000Z"),
 *         @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-01T00:00:00.000000Z"),
 *         @OA\Property(property="games_won_percentage", type="number", format="float", example=40)
 *     ),
 *     @OA\Schema(
 *         schema="ListAllPlayersWithStats",
 *         type="object",
 *         @OA\Property(
 *             property="data",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Player")
 *         ),
 *         @OA\Property(property="average_percentage_of_games_won", type="number", format="float", example=12)
 *     ),
 *     @OA\Schema(
 *         schema="Game",
 *         type="object",
 *         @OA\Property(property="dice_1", type="integer", example=4),
 *         @OA\Property(property="dice_2", type="integer", example=3),
 *         @OA\Property(property="result", type="string", example="win")
 *     ),
 *     @OA\Schema(
 *         schema="ListPlayerGamesWithStats",
 *         type="object",
 *         @OA\Property(
 *             property="data",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Game")
 *         ),
 *         @OA\Property(property="games_won_percentage", type="number", format="float", example=40)
 *     )
 * )
 */

class Controller
{
}
