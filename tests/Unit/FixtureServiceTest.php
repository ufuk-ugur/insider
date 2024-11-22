<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\Team;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_games(): void
    {
        Game::factory()->count(10)->create();
        $fixtureService = new FixtureService();
        $fixtureService->resetGames();
        $this->assertDatabaseCount('games', 0);
    }

    public function test_generate_fixtures(): void
    {
        Team::factory()->count(4)->create();
        $fixtureService = new FixtureService();
        $fixtures = $fixtureService->generateFixtures();
        $this->assertCount(6, $fixtures); // 4 teams, 6 weeks (round robin home/away)
    }

    public function test_rotate_teams(): void
    {
        $teamIds = [1, 2, 3, 4];
        $fixtureService = new FixtureService();
        $this->invokeMethod($fixtureService, 'rotateTeams', [&$teamIds]);
        $this->assertEquals([1, 4, 2, 3], $teamIds); // Check correct rotation
    }

    public function test_save_fixtures(): void
    {
        $teams = Team::factory()->count(4)->create();

        $fixtures = [
            [
                ['home_team_id' => $teams[0]->id, 'away_team_id' => $teams[1]->id],
                ['home_team_id' => $teams[2]->id, 'away_team_id' => $teams[3]->id]
            ],
            [
                ['home_team_id' => $teams[0]->id, 'away_team_id' => $teams[2]->id],
                ['home_team_id' => $teams[1]->id, 'away_team_id' => $teams[3]->id]
            ]
        ];

        $fixtureService = new FixtureService();
        $fixtureService->saveFixtures($fixtures);

        $this->assertDatabaseCount('games', 4);
        $this->assertDatabaseHas('games', ['home_team_id' => $teams[0]->id, 'away_team_id' => $teams[1]->id, 'week' => 1]);
        $this->assertDatabaseHas('games', ['home_team_id' => $teams[2]->id, 'away_team_id' => $teams[3]->id, 'week' => 1]);
        $this->assertDatabaseHas('games', ['home_team_id' => $teams[0]->id, 'away_team_id' => $teams[2]->id, 'week' => 2]);
        $this->assertDatabaseHas('games', ['home_team_id' => $teams[1]->id, 'away_team_id' => $teams[3]->id, 'week' => 2]);
    }

    public function test_get_fixtures_grouped_by_week(): void
    {
        $teams = Team::factory()->count(4)->create();

        Game::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id,
            'week'         => 1
        ]);
        Game::factory()->create([
            'home_team_id' => $teams[2]->id,
            'away_team_id' => $teams[3]->id,
            'week'         => 1
        ]);
        Game::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[2]->id,
            'week'         => 2
        ]);

        $fixtureService = new FixtureService();
        $weeks = $fixtureService->getFixturesGroupedByWeek();

        $this->assertCount(2, $weeks);
        $this->assertCount(2, $weeks[1]);
        $this->assertCount(1, $weeks[2]);
    }

    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
