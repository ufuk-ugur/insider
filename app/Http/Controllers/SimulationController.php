<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Services\SimulationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SimulationController extends Controller
{
    public function __invoke(SimulationService $simulationService, Request $request): Response
    {
        $week = $request->get('week');

        $maxWeek = Game::max('week');

        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where('is_played', false)
            ->when($week, function ($query) use ($week) {
                return $query->where('week', $week);
            })
            ->get();

        $simulationService->simulateGames($games);

        $week ??= $maxWeek;

        $games = Game::with(['homeTeam', 'awayTeam'])
            ->where('week', $week)
            ->get();

        $board = Team::orderBy('points', 'desc')->orderBy('gd', 'desc')->get();

        return Inertia::render('Simulation', [
            'board'   => $board,
            'games'   => $games,
            'week'    => $week,
            'maxWeek' => $maxWeek,
        ]);
    }
}
