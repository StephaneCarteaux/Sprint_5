<?php

namespace Tests\Feature\Controller\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;

class LoginControllerTest extends TestCase
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

    public function testUserCannotLoginWithWrongEmail()
    {
        $loginData = [
            'email'     => 'wrongemail',
            'password'  => 'password123',
        ];

        $response = $this->json('POST', '/api/v1/login', $loginData);
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
