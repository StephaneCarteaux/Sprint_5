<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class RankingControllerTest extends TestCase
{
    // Get ranking
    public function testUserCanGetRanking()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking');
        $response->assertStatus(Response::HTTP_OK);
    }

    // Get loser
    public function testUserCanGetLoser()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/loser');
        $response->assertStatus(Response::HTTP_OK);
    }

    // Get winner
    public function testUserCanGetWinner()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/winner');
        $response->assertStatus(Response::HTTP_OK);
    }
}
