<?php

namespace App\Models;

use App\Services\SimulationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id', 'away_team_id', 'is_played', 'week', 'home_score', 'away_score'
    ];

    protected $casts = [
        'home_team_id' => 'integer',
        'away_team_id' => 'integer',
        'is_played'    => 'boolean',
        'week'         => 'integer',
        'home_score'   => 'integer',
        'away_score'   => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::updated(function ($game) {
            $simulationService = new SimulationService();

            $simulationService->updateTeamStatistics();
        });
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
