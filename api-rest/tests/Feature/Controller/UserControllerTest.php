<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;

class UserControllerTest extends TestCase
{
    // Update
    public function testUserCannotUpdatWithEmptyName()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'name' => '',
            ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid(['name']);
    }

    public function testUserCannotUpdatWithoutToken()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = null;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'name' => 'Updated Test User',
            ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUserCannotUpdateOtherUser()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/1", [
                'name' => 'Updated Test User',
            ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUserCanUpdateSuccessfully()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('PUT', "/api/v1/players/{$user->id}", [
                'name' => 'Updated Test User',
            ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'name' => 'Updated Test User',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Updated Test User',
        ]);
    }

    // Index
    public function testAdminUserCanIndex()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'admin@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
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

    public function testNonAminUserCannotIndex()
    {
        //$this->withoutExceptionHandling();

        $user = User::where('email', 'test@example.com')->first();
        $token = $user->createToken('TestToken')->accessToken;
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($headers)
            ->json('GET', 'api/v1/players');

        $response
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
