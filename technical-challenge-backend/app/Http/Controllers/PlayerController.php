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
        $players = $data["game"]["players"];
        $player1 = $data["game"]["players"][0];
        $player2 = $data["game"]["players"][1];
        $totalScore = $player1["score"] + $player2["score"];
        $playerToScore = $data["player"];
        $adversary = Player::find($player1or2 === 1 ? $player2["id"] : $player1["id"]);

        // update the model with new data
        $player->fill([
            "name" => $playerToScore["name"],
            "score" => $playerToScore["score"] + 1,
            "won" => $playerToScore["won"]
        ]);

        // If both players have 20 or more points we're in a state of deuce
        if ($player1["score"] >= 3 && $player2["score"] >= 3) {
            $game->fill(["deuce" => 1]);
        };

        // If a point has passed and we're in deuce then change service        
        if ($game["deuce"] === 1) {

            $game["service"] === 0 ?
                $game->fill(["service" => 1]) : $game->fill(["service" => 0]);

            if (abs($player["score"] - $adversary["score"]) > 2) {
                $player->fill(["won" => 1]);
                $adversary->fill(["won" => 2]);
            }
        }

        // Else, change service every 2 serves
        if ($game["deuce"] === 0) {

            if ($totalScore % 2 === 0) {
                $game["service"] === 0 ?
                    $game->fill(["service" => 1]) : $game->fill(["service" => 0]);
            }

            if ($player["score"] > 5 && $player["score"] > $adversary["score"]) {

                $player->fill(["won" => 1]);
                $adversary->fill(["won" => 2]);
            }
        }



        // don't need to associate with game as shouldn't have changed
        // but $game required for route model binding
        // save the player
        $adversary->save();
        $player->save();
        $game->save();


        // return the updated player
        return [new PlayerResource($player), new GameResource($game)];
    }
}
