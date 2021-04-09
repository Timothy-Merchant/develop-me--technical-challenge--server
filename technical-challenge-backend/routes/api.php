<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{user}', [UserController::class, "store"]);
Route::post('/users', [UserController::class, "store"]);
Route::put('/users/{user}', [UserController::class, "update"]);
Route::delete('/users/{user}', [UserController::class, "destroy"]);

// Create Players for the game (from the frontend Roster list)
Route::put('/players/{player}', [PlayerController::class, 'update']);

// Routes that are based on rounds

Route::group(["prefix" => "tournaments"], function () {
    Route::get("", [TournamentController::class, "index"]);
    Route::post("", [TournamentController::class, "startTournament"]);

    Route::group(["prefix" => "{tournament}"], function () {
        Route::get("", [TournamentController::class, "show"]);
        Route::put("", [TournamentController::class, "endTournament"]);
        Route::delete("", [TournamentController::class, "destroy"]);

        Route::group(["prefix" => "rounds"], function () {
            Route::get("", [RoundController::class, "index"]);
            Route::post("", [RoundController::class, "store"]);

            Route::group(["prefix" => "{round}"], function () {
                Route::get("", [RoundController::class, "show"]);
                Route::put("", [RoundController::class, "update"]);
                Route::delete("", [RoundController::class, "destroy"]);

                Route::group(["prefix" => "games"], function () {
                    Route::get("", [GameController::class, "index"]);
                    Route::post("", [GameController::class, "store"]);

                    Route::group(["prefix" => "{game}"], function () {
                        Route::get("", [GameController::class, "show"]);
                        Route::put("", [GameController::class, "update"]);
                        Route::delete("", [GameController::class, "destroy"]);

                        Route::group(["prefix" => "players"], function () {
                            Route::get("", [PlayerController::class, "index"]);
                            Route::post("", [PlayerController::class, "store"]);

                            Route::group(["prefix" => "{player}"], function () {
                                Route::get("", [PlayerController::class, "show"]);
                                Route::put("", [PlayerController::class, "update"]);
                                Route::delete("", [PlayerController::class, "destroy"]);
                            });
                        });
                    });
                });
            });
        });
    });
});
