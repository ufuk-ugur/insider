<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = ['Liverpool', 'Manchester City', 'Chelsea', 'Arsenal'];

        foreach ($teams as $team) {
            Team::factory()->create(['name' => $team]);
        }
    }
}
