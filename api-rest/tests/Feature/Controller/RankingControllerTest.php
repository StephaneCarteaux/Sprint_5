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

    public function testGetRankingReturnsCorrectStructure()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'nickname',
                    'games_won_percentage',
                ]
            ]
        ]);
    }

    // Get loser
    public function testUserCanGetLoser()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/loser');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetLoserReturnsCorrectStructure()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/loser');

        $response->assertJsonStructure([
            'data' => [
                'nickname',
                'games_won_percentage',
            ]
        ]);
    }

    // Get winner
    public function testUserCanGetWinner()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/winner');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetWinnerReturnsCorrectStructure()
    {
        //$this->withoutExceptionHandling();

        $response = $this->json('GET', '/api/v1/players/ranking/winner');

        $response->assertJsonStructure([
            'data' => [
                'nickname',
                'games_won_percentage',
            ]
        ]);
    }
}
