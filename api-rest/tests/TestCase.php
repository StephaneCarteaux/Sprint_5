<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



abstract class TestCase extends BaseTestCase
{
    // Trait that executes migrations before each test
    use RefreshDatabase;

    // Trait that disables middleware
    //use WithoutMiddleware;

    // Seed the database before each test
    protected $seed = true;

    // Mock console output
    public $mockConsoleOutput = false;

    // Access token
    protected $accessToken;

    // Set up
    public function setUp(): void
    {
        // Call the parent's setUp method
        parent::setUp();

        // Create personal client access token for testing
        $this->artisan('passport:client --personal --no-interaction');

        // Create test user
        $user = User::factory()->create([
            'name'          => 'Test User',
            'nickname'      => 'test',
            'registered_at' => now(),
            'email'         => 'test@example.com',
            'password'      => Hash::make('password123'),
            
        ])->assignRole('player');
        
        $this->accessToken = $user->createToken('TestToken')->accessToken;
    }
}
