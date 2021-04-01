<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoundController;

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
        Route::get("/games/{game}", [GameController::class, "show"]);
        Route::get("/games/{game}/players", [PlayerController::class, "index"]);
        Route::get("/games/{game}/players/{player}", [PlayerController::class, "show"]);
    });
    // Route::group(["prefix" => "{round}"], function () {

    // });
});
