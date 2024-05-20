<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterUser(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/v1/players', [
            'name' => 'test',
            'nickname' => 'test',
            'email' => 'test@test.com',
            'password' => ('111111'),
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'nickname' => 'test',
            'email' => 'test@test.com',
        ]); 

        $user = User::first();
        $this->assertEquals(Hash::check('111111', $user->password), true);
    }
}
