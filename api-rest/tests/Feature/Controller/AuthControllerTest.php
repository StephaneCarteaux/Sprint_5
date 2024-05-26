<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;

class AuthControllerTest extends TestCase
{
    // Login
    public function testUserCanLoginSuccessfully()
    {
        $loginData = [
            'email'     => 'test@example.com',
            'password'  => 'password123',
        ];

        $response = $this->json('POST', '/api/v1/login', $loginData);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'token',
            ]);

        // Check that the token is in the response
        $this->assertArrayHasKey('token', $response->json());
    }

    public function testUserCannotLoginWithWrongPassword()
    {
        //$this->withoutExceptionHandling();
        $loginData = [
            'email'     => 'test@example.com',
            'password'  => 'wrongpassword',
        ];

        $response = $this->json('POST', '/api/v1/login', $loginData);
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    // Create a new user
    public function testUserIsCreatedSuccessfully()
    {
        $payload = [
            'name'      => fake()->name,
            'nickname'  => fake()->userName,
            'email'     => fake()->email,
            'password'  => ((fake()->password)),
        ];

        $response = $this->json('POST', 'api/v1/players', $payload);
        $response->assertStatus(Response::HTTP_CREATED);

        // Check that the user is in the database
        $this->assertDatabaseHas('users', [
            'name'      => $payload['name'],
            'nickname'  => $payload['nickname'],
            'email'     => $payload['email'],
        ]);

        $this->assertTrue(
            Hash::check($payload['password'], User::where('email', $payload['email'])->first()->password)
        );
    }

    public function testRequiredFieldsAreNotProvided()
    {
        $payload = [
            'name'      => '',
            'email'     => '',
            'password'  => '',
        ];

        // Check that the email is not in the database
        $response = $this->json('post', 'api/v1/players', $payload);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid(['name'])
            ->assertInvalid(['email'])
            ->assertInvalid(['password']);
    }

    public function testEmailIsUnique()
    {
        $payload = [
            'name'      => 'Test User',
            'nickname'  => 'test2',
            'email'     => 'test@example.com',
            'password'  => ((fake()->password)),
        ];

        // Check that the email is not in the database
        $response = $this->json('post', 'api/v1/players', $payload);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid(['email']);
    }

    public function testNicknameIsUnique()
    {
        $payload = [
            'name'      => 'Test User',
            'nickname'  => 'test',
            'email'     => 'test2@example.com',
            'password'  => ((fake()->password)),
        ];

        // Check that the nickname is not in the database
        $response = $this->json('post', 'api/v1/players', $payload);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertInvalid(['nickname']);
    }

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
