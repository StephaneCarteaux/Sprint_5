<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;

class GameControllerTest extends TestCase
{
    public function testAuthenticatedUserCanPlay()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'email'     => $user->email,
        ];

        $response = $this->withHeaders($headers)
            ->json('POST', "/api/v1/players/{$user->id}/games", $payload);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testNotAuthenticatedUserCannotPlay()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = null;
        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'email'     => $user->email,
        ];

        $response = $this->withHeaders($headers)
            ->json('POST', "/api/v1/players/{$user->id}/games", $payload);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
