<?php

use App\Http\Controllers\FixtureController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SimulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);
Route::get('fixture', FixtureController::class);
Route::post('simulation', SimulationController::class)->name('simulation');
