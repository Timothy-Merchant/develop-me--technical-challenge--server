<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use PDO;
use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PlayerResource;

class PlayerController extends Controller
{
    public function index(Tournament $tournament, Round $round, Game $game)
    {
        // return all comments for a specific round
        // uses Eloquent's magic relationship properties
        return PlayerResource::collection($game->players);
    }

    // don't actually use $game, but required for route model binding
    public function show(Round $round, Game $game, Player $player)
    {
        // return the player
        return new PlayerResource($player);
    }

    // get the $game using Route Model Binding
    public function store(Request $request, Round $round, Game $game)
    {
        $data = $request->all();
        // create a new player with the data
        $player = new Player($data);
        // associate the player with the game
        $player->game()->associate($game);
        // save the player in the DB
        $player->save();
        // return the new $player
        return new PlayerResource($player);
    }

    // don't actually use $game, but required for route model binding
    public function destroy(Round $round, Game $game, Player $player)
    {
        // delete the game
        $player->delete();
        // return an empty response
        return response(null, 204);
    }

    public function update(Request $request, Tournament $tournament, Round $round, Game $game, Player $player)
    {
        $data = $request->all();

        $player1or2 = $data["player1or2"];
        $deuce = $data["game"]["deuce"];
        $service = $data["game"]["service"];
        $player1 = $data["game"]["players"][0];
        $player2 = $data["game"]["players"][1];
        $totalScore = $player1["score"] + $player2["score"];
        $adversary = Player::find($player1or2 === 1 ? $player2['id'] : $player1['id']);
        $newPlayer = Player::find($player1or2 === 2 ? $player2['id'] : $player1['id']);

        if ($player1or2 === 1) {
            $newPlayer->fill(["name" => $player1["name"], "score" => $player1["score"] + 1, "won" => 0]);
            $adversary->fill(["name" => $player2["name"], "won" => 0]);
        } else {
            $newPlayer->fill(["name" => $player2["name"], "score" => $player2["score"] + 1, "won" => 0]);
            $adversary->fill(["name" => $player1["name"], "won" => 0]);
        }

        // If both players have 20 or more points we're in a state of deuce
        if ($newPlayer["score"] >= 10 && $adversary["score"] >= 10) {
            $game->fill(["deuce" => 1]);
        };

        // If a point has passed and we're in deuce then change service        
        if ($game["deuce"] === 1) {

            $game["service"] === 0 ?
                $game->fill(["service" => 1]) : $game->fill(["service" => 0]);

            if (abs($newPlayer["score"] - $adversary["score"]) > 1) {
                $newPlayer->fill(["won" => 1]);
                $adversary->fill(["won" => 2]);
            }
        }

        // Else, change service every 2 serves
        if ($game["deuce"] === 0) {

            if ($totalScore % 2 === 0 && $totalScore > 1) {
                $game["service"] === 0 ?
                    $game->fill(["service" => 1]) : $game->fill(["service" => 0]);
            }

            if (($newPlayer["score"] > 10) && ($newPlayer["score"] > $adversary["score"])) {

                $newPlayer->fill(["won" => 1]);
                $adversary->fill(["won" => 2]);
            }
        }

        // Update the players for the game to be returned to the frontend.
        $player1or2 === 1 ?
            $game->fill(["players" => [$newPlayer, $adversary]]) :
            $game->fill(["players" => [$adversary, $newPlayer]]);

        // Save the updated game and players to the database
        $adversary->save();
        $newPlayer->save();
        $game->save();

        // return the updated game and players to the frontend.
        return [$newPlayer, $adversary, $game];
    }
}
