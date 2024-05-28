<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    // Update
    public function testAuthenticatedUserCannoChangeNicknameWithEmptyNickname()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'nickname' => '',
            ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid(['nickname']);
    }

    public function testAuthenticatedUserCannotChangeNicknameWithoutToken()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = null;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'nickname' => 'Updated Nickname',
            ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthenticatedUserCannotChangeNicknameForAnotherUser()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/1", [
                'nickname' => 'Updated Nickname',
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testAuthenticatedUserCanChangeNicknameSuccessfully()
    {
        $this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'nickname' => 'Updated Nickname',
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'nickname' => 'Updated Nickname',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'nickname' => 'Updated Nickname',
        ]);
    }

    // Index
    public function testAdminCanlistAllPlayersWithStats()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'admin@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', 'api/v1/players');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
                'average_percentage_of_games_won',
            ]);
    }

    public function testNonAminUserCannotlistAllPlayersWithStats()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('Personal Access Token')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', 'api/v1/players');

        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
