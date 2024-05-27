<?php

namespace Tests\Feature\Controller\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;

class RegisterControllerTest extends TestCase
{
    // Register new user
    public function testUserIsRegisteredSuccessfully()
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
}
