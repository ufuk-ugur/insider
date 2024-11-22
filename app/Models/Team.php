<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'power',
        'won',
        'drawn',
        'lost',
        'gf',
        'ga',
        'gd',
        'points',
        'predicted_rank',
    ];

    protected $casts = [
        'id'             => 'integer',
        'name'           => 'string',
        'power'          => 'integer',
        'won'            => 'integer',
        'drawn'          => 'integer',
        'lost'           => 'integer',
        'gf'             => 'integer',
        'ga'             => 'integer',
        'gd'             => 'integer',
        'points'         => 'integer',
        'predicted_rank' => 'float',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }
}
