<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
        ], Response::HTTP_OK);
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Assign 'Anonim' if nickname is not provided or null
        $nickname = $request->input('nickname') ?? 'Anonim';

        // If nickname is provided, check if it already exists
        if ($request->filled('nickname')) {
            $existingNickname = DB::table('users')->where('nickname', $nickname)->exists();
            // If nickname already exists, return error
            if ($existingNickname) {
                return response()->json(['error' => 'Duplicate nickname not allowed'], 400);
            }
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

        $token = $user->createToken('Personal Access Token')->accessToken;
    
        return response()->json([
            'message' => 'Successfully registered',
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    
}
