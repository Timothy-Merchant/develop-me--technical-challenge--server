<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GameResource;

class GameController extends Controller
{

    public function index(Tournament $tournament, Round $round)
    {
        // return all comments for a specific round
        // uses Eloquent's magic relationship properties
        return GameResource::collection($round->games);
    }

    // don't actually use $round, but required for route model binding
    public function show(Round $round, Game $game)
    {
        // return the game
        return new GameResource($game);
    }

    // get the $round using Route Model Binding
    public function store(Request $request, Round $round, Tournament $tournament)
    {
        $data = $request->all();
        // create a new game with the data
        $game = new Game($data);
        // associate the game with a round
        $game->round()->associate($round);
        // save the game in the DB
        $game->save();
        // return the new $game
        // return new GameResource($game);
        return $data;
    }

    // don't actually use $round, but required for route model binding
    public function destroy(Round $round, Game $game)
    {
        // delete the game
        $game->delete();
        // return an empty response
        return response(null, 204);
    }

    public function update(Request $request, Tournament $tournament, Round $round, Game $game)
    {
        $data = $request->all();        

        $players = $data["players"];        

        DB::table('players')
            ->where('id', $players[0]["id"])
            ->update(['won' => $players[0]["won"]]);

        DB::table('players')
            ->where('id', $players[1]["id"])
            ->update(['won' => $players[1]["won"]]);

        $game->fill($data);

        $game->update(["complete" => 1]);

        $newGame = new GameResource($game);

        return [$newGame, $players];
    }
}
