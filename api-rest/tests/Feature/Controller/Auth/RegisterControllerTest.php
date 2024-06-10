<?php

namespace Tests\Feature\Controller\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

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

        // Check that user has role "player"
        $this->assertDatabaseHas('model_has_roles', [
            'role_id'   => Role::where('name', 'player')->first()->id,
            'model_id'  => User::where('email', $payload['email'])->first()->id,
        ]);

        // Check that the password is set correctly
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
