<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LoginController extends Controller
{
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
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
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
