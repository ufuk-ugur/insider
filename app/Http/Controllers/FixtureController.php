<?php

namespace App\Http\Controllers;

use App\Services\FixtureService;
use Inertia\Inertia;
use Inertia\Response;

class FixtureController extends Controller
{
    public function __invoke(FixtureService $fixtureService): Response
    {
        $fixtureService->resetGames();
        $fixtures = $fixtureService->generateFixtures();
        $fixtureService->saveFixtures($fixtures);

        $weeks = $fixtureService->getFixturesGroupedByWeek();

        return Inertia::render('Fixture', ['weeks' => $weeks]);
    }
}
