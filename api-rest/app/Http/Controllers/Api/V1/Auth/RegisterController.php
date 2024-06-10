<?php

namespace App\Http\Controllers\Api\V1\Auth;

use OpenApi\Annotations as OA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/players",
     *     tags={"Register"},
     *     summary="Registers a new user",
     *     description="Registers a new user in the application.",
     *     operationId="register",
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="nickname", type="string", example="john_doe", nullable=true),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully registered"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *     ),
     * )
     */

    // Register
    public function register(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Assign nickname if not provided
        $nickname = $request->input('nickname') ?? 'Anonim';

        // If nickname is not unique, return error
        if ($nickname !== 'Anonim' && User::where('nickname', $nickname)->exists()) {
            return response()->json([
                'message' => 'The nickname has already been taken.',
                'errors' => ['nickname' => ['The nickname has already been taken.']],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create new user if nickname is unique
        $user = User::create([
            'name' => $request->name,
            'nickname' => $nickname,
            'registered_at' => now(),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('player');

        // Get token
        $token = $user->createToken('Personal Access Token')->accessToken;

        // Return response
        return response()->json([
            'message' => 'Successfully registered',
            'token' => $token,
        ], Response::HTTP_CREATED);
    }
}
