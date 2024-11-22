<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FixtureService
{
    public function resetGames(): void
    {
        Game::truncate();
    }

    public function generateFixtures(): array
    {
        $teams = Team::all();
        $teamCount = $teams->count();
        $teamIds = $teams->shuffle()->pluck('id')->toArray();
        $fixtures = [];

        for ($round = 0; $round < $teamCount - 1; $round++) {
            $fixtures[] = $this->generateRoundFixtures($teamIds, $teamCount);
            $this->rotateTeams($teamIds);
        }

        $fixtures = collect($fixtures);
        return $fixtures->concat($this->generateReverseFixtures($fixtures->toArray()))->toArray();
    }

    private function generateRoundFixtures(array $teamIds, int $teamCount): array
    {
        $matchesPerRound = $teamCount / 2;
        $roundFixtures = [];

        for ($match = 0; $match < $matchesPerRound; $match++) {
            $home = Arr::get($teamIds, $match);
            $away = Arr::get($teamIds, $teamCount - 1 - $match);

            if ($home !== null && $away !== null) {
                $roundFixtures[] = [
                    'home_team_id' => $home,
                    'away_team_id' => $away,
                ];
            }
        }

        return $roundFixtures;
    }

    private function rotateTeams(array &$teamIds): void
    {
        $lastTeam = Arr::pull($teamIds, count($teamIds) - 1);
        array_splice($teamIds, 1, 0, $lastTeam);
    }

    private function generateReverseFixtures(array $fixtures): array
    {
        $reverseFixtures = [];

        foreach ($fixtures as $roundFixtures) {
            $reverseRound = [];

            foreach ($roundFixtures as $fixture) {
                $reverseRound[] = [
                    'home_team_id' => Arr::get($fixture, 'away_team_id'),
                    'away_team_id' => Arr::get($fixture, 'home_team_id'),
                ];
            }

            $reverseFixtures[] = $reverseRound;
        }

        return $reverseFixtures;
    }

    public function saveFixtures(array $fixtures): void
    {
        foreach ($fixtures as $week => $roundFixtures) {
            foreach ($roundFixtures as $fixture) {
                Game::create([
                    'home_team_id' => Arr::get($fixture, 'home_team_id'),
                    'away_team_id' => Arr::get($fixture, 'away_team_id'),
                    'is_played'    => false,
                    'week'         => $week + 1,
                ]);
            }
        }
    }

    public function getFixturesGroupedByWeek(): Collection
    {
        $games = Game::with(['homeTeam', 'awayTeam'])->get();
        return $games->groupBy('week');
    }
}
