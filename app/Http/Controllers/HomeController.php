<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $teams = Team::all();
        return Inertia::render('Home', [
            'teams' => $teams
        ]);
    }
}
