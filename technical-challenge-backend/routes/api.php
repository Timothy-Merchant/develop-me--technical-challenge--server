<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// <!-- Post Players -->
// POST api/players
// <!-- This will then create rounds and games -->

// Get Rounds
// <!-- Get all rounds -->
// GET api/rounds
// <!-- Get a single round -->
// GET api/rounds/1
// <!-- Get all games for a single round -->
// GET api/rounds/1/games
// <!-- Get a single game -->
// GET api/rounds/1/games/1
// <!-- Get players for a specific game -->
// GET api/rounds/1/games/1/players

// One to Many relationships:

// Rounds (Have Many Games) 
// Games (Have Many Players)

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
