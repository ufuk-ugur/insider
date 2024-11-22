<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SimulationService
{
    public function simulateGames(Collection $games): void
    {
        $games->each(function ($game) {
            $result = $this->simulateGame($game->homeTeam, $game->awayTeam);

            $game->update([
                'home_score' => Arr::get($result, 'home_score'),
                'away_score' => Arr::get($result, 'away_score'),
                'is_played'  => true,
            ]);
        });
    }

    public function simulateGame(Team $homeTeam, Team $awayTeam): array
    {
        $homeAdvantage = 10;
        $homeChance = $homeTeam->power + $homeAdvantage;
        $awayChance = $awayTeam->power;

        $homeScore = random_int(0, max(1, $homeChance / 20));
        $awayScore = random_int(0, max(1, $awayChance / 20));

        return ['home_score' => $homeScore, 'away_score' => $awayScore];
    }

    public function updateTeamStatistics(): void
    {
        Team::all()->each(function ($team) {
            $playedGames = $team->homeGames->merge($team->awayGames)->where('is_played', true);

            $won = $playedGames->filter(function ($game) use ($team) {
                return ($game->home_team_id == $team->id && $game->home_score > $game->away_score) ||
                    ($game->away_team_id == $team->id && $game->away_score > $game->home_score);
            })->count();

            $drawn = $playedGames->filter(function ($game) {
                return $game->home_score == $game->away_score;
            })->count();

            $lost = $playedGames->filter(function ($game) use ($team) {
                return ($game->home_team_id == $team->id && $game->home_score < $game->away_score) ||
                    ($game->away_team_id == $team->id && $game->away_score < $game->home_score);
            })->count();

            $gf = $playedGames->sum(function ($game) use ($team) {
                return $game->home_team_id == $team->id ? $game->home_score : $game->away_score;
            });

            $ga = $playedGames->sum(function ($game) use ($team) {
                return $game->home_team_id == $team->id ? $game->away_score : $game->home_score;
            });

            $gd = $gf - $ga;
            $points = $won * 3 + $drawn;


            $totalPoints = Team::max('points') ?: 1;
            $totalGoalDifference = Team::max('gd') ?: 1;

            $pointsShare = $points ? $points / $totalPoints : 0;
            $wonGamesShare = count($playedGames) ? $won / count($playedGames) : 0;
            $goalDifferenceShare = $gd ? $gd / $totalGoalDifference : 0;


            $performanceScore = ($pointsShare * 0.5) + ($wonGamesShare * 0.3) + ($goalDifferenceShare * 0.2);


            $performanceScore = max(0, min(1, $performanceScore));

            $probability = $performanceScore * 100;

            $performanceScore = round($probability, 2);


            $team->update([
                'won'            => $won,
                'drawn'          => $drawn,
                'lost'           => $lost,
                'gf'             => $gf,
                'ga'             => $ga,
                'gd'             => $gd,
                'points'         => $points,
                'predicted_rank' => $performanceScore,
            ]);
        });
    }
}
