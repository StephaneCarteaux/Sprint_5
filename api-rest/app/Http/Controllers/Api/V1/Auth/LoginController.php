<?php

namespace App\Http\Controllers\Api\V1\Auth;

use OpenApi\Annotations as OA;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Login"},
     *     summary="Logs a user in",
     *     description="Logs a user into the application.",
     *     operationId="login",
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged in"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEyMzQ1Njc4OTAifQ=="),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="The provided credentials are incorrect",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="The provided credentials are incorrect",
     *     ),
     * )
     */

    // Login
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], Response::HTTP_FORBIDDEN);
        }

        // Create token
        $token = $user->createToken('Personal Access Token')->accessToken;

        // Return response
        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
        ], Response::HTTP_OK);
    }
}
