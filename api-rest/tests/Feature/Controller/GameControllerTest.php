<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;

class GameControllerTest extends TestCase
{
    // Index
    public function testAthenticatedUserCanlistPlayerGamesWithStats(){

        //$this->withoutExceptionHandling();
        
        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', "/api/v1/players/{$user->id}/games");
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testNonAthenticatedUserCannotlistPlayerGamesWithStats(){

        //$this->withoutExceptionHandling();
        
        $user = User::where('email', 'test@example.com')->first();
        $token = null;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', "/api/v1/players/{$user->id}/games");
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAthenticatedUserCannotlistPlayerGamesWithStatsForAnotherUser(){

        //$this->withoutExceptionHandling();
        
        $user = User::where('id', '1')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', "/api/v1/players/2/games");
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    // Play a game
    public function testAuthenticatedUserCanPlay()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            'email'     => $user->email,
        ];

        $response = $this->withHeaders($headers)
            ->json('POST', "/api/v1/players/{$user->id}/games", $payload);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testNonAuthenticatedUserCannotPlay()
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

    // Delete
    public function testAuthenticatedUserCanDeletePlayerGames()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('DELETE', "/api/v1/players/{$user->id}/games");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Player games deleted successfully'
        ]);
        $this->assertDatabaseMissing('games', [
            'user_id'   => $user->id
        ]);
    }

    public function testNonAuthenticatedUserCanDeletePlayerGames()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = null;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('DELETE', "/api/v1/players/{$user->id}/games");
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticatedUserCannotDeletePlayerGamesForAnotherUser()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('id', '1')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('DELETE', "/api/v1/players/2/games");
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
