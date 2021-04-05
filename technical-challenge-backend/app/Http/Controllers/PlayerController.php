<?php

namespace App\Http\Controllers;

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

        $deuce = $data["game"]["deuce"];
        $service = $data["game"]["service"];
        $players = $data["game"]["players"];
        $playerToScore = $data["player"];

        // DB::table('players')
        //     ->where('id', $playerToScore["id"])
        //     ->update(['score' => $playerToScore["score"] + 1]);

        // DB::table('games')
        //     ->where('id', $players[0]["id"])
        //     ->update(['won' => $players[0]["won"]]);

        // DB::table('players')
        //     ->where('id', $players[1]["id"])
        //     ->update(['won' => $players[1]["won"]]);


        // update the model with new data
        $player->fill(["score" => $playerToScore["score"] + 1]);

        // don't need to associate with game as shouldn't have changed
        // but $game required for route model binding
        // save the player
        $player->save();

        // return the updated player
        return new PlayerResource($player);
    }
}
