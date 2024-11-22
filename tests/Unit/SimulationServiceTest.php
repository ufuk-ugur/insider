<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\Team;
use App\Services\SimulationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SimulationService $simulationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simulationService = new SimulationService();
    }

    public function testSimulateGame()
    {
        $homeTeam = Team::factory()->create(['power' => 80]);
        $awayTeam = Team::factory()->create(['power' => 70]);

        $result = $this->simulationService->simulateGame($homeTeam, $awayTeam);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('home_score', $result);
        $this->assertArrayHasKey('away_score', $result);
        $this->assertIsInt($result['home_score']);
        $this->assertIsInt($result['away_score']);
    }

    public function testSimulateWeek()
    {
        $week = 1;
        $teams = Team::factory()->count(4)->create();

        Game::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id,
            'week'         => $week,
        ]);

        Game::factory()->create([
            'home_team_id' => $teams[2]->id,
            'away_team_id' => $teams[3]->id,
            'week'         => $week,
        ]);

        $games = Game::all();

        $this->simulationService->simulateGames($games);

        $this->assertCount(2, $games);

        foreach ($games as $game) {
            $this->assertTrue($game->is_played);
            $this->assertNotNull($game->home_score);
            $this->assertNotNull($game->away_score);
        }
    }

    public function testUpdateTeamStatistics()
    {
        $team = Team::factory()->create(['power' => 80]);

        Game::factory()->count(2)->create([
            'home_team_id' => $team->id,
            'away_team_id' => Team::factory()->create()->id,
            'is_played'    => true,
            'home_score'   => 2,
            'away_score'   => 1,
        ]);

        $this->simulationService->updateTeamStatistics();

        $team->refresh();

        $this->assertEquals(2, $team->won);
        $this->assertEquals(0, $team->drawn);
        $this->assertEquals(0, $team->lost);
        $this->assertEquals(4, $team->gf);
        $this->assertEquals(2, $team->ga);
        $this->assertEquals(2, $team->gd);
        $this->assertEquals(6, $team->points);
    }
}
