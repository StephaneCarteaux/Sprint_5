<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
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

        // Get token
        $token = $user->createToken('Personal Access Token')->accessToken;

        // Return response
        return response()->json([
            'message' => 'Successfully registered',
            'token' => $token,
        ], Response::HTTP_CREATED);
    }
}
