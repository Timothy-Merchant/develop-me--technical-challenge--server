<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\PlayerController;

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

// Create Players for the game (from the frontend Roster list)
Route::post('/players', [PlayerController::class, 'store']);

// Routes that are based on rounds
Route::group(["prefix" => "rounds"], function () {

    Route::get("/", [RoundController::class, "index"]);
    Route::post("/", [RoundController::class, "store"]);
    Route::put("/", [RoundController::class, "update"]);
    Route::delete("/", [RoundController::class, "destroy"]);
    Route::get("/{round}", [RoundController::class, "show"]);

    Route::group(["prefix" => "{round}"], function () {

        Route::get("/games", [GameController::class, "index"]);
        Route::post("/games", [GameController::class, "store"]);
        Route::get("/games/{game}", [GameController::class, "show"]);
        Route::put("/games/{game}", [GameController::class, "update"]);
        Route::delete("/games/{game}", [GameController::class, "destroy"]);

        Route::group(["prefix" => "{game}"], function () {

            Route::get("/players", [PlayerController::class, "index"]);
            Route::post("/players", [PlayerController::class, "store"]);
            Route::get("/players/{player}", [PlayerController::class, "show"]);
            Route::put("/players/{player}", [PlayerController::class, "update"]);
            Route::delete("/players/{player}", [PlayerController::class, "destroy"]);
        });
    });
});
