<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GameResource;
use App\Http\Resources\RoundResource;

class RoundController extends Controller
{
    public function index(Tournament $tournament, Round $round)
    {
        return $tournament->rounds;
    }

    public function show(Round $round)
    {
        // just return the round
        return $round;
    }

    public function store(Request $request, Tournament $tournament)
    {
        $data = $request->all();

        $rounds = $data["rounds"];
        $games = $data["games"];
        $players = $data["players"];

        foreach ($rounds as $round) {
            $newRound = Round::create($round)->tournament()->associate($tournament);

            foreach ($round["games"] as $game) {

                $newGame = Game::create([
                    "round_id" => $newRound["id"],
                    "deuce" => 0,
                    "service" => 0,
                    "complete" => 0
                ])->round()->associate($newRound);

                if (isset($game["player1"]["name"])) {
                    $player1 = $game["player1"]["name"];
                }

                if (isset($game["player2"]["name"])) {
                    $player2 = $game["player2"]["name"];
                }

                if ($player1 !== "") {
                    $newPlayer = Player::create([
                        "game_id" => $newGame["id"],
                        "name" => $player1,
                        "score" => 0,
                        "won" => 0,
                    ])->game()->associate($newGame);
                }

                if ($player2 !== "") {
                    $newPlayer = Player::create([
                        "game_id" => $newGame["id"],
                        "name" => $player2,
                        "score" => 0,
                        "won" => 0,
                    ])->game()->associate($newGame);
                }
            }
        }

        // $data = $request->all();     
        // $animal = new Animal($data);                 
        // $animal->owner()->associate($owner);
        // $animal->save();
        // $animal->setTreatments($request->get("treatments"));
        // return new AnimalResource($animal);

        // foreach ($players as $player) {
        //     Player::create($player);
        // }

        return $rounds;
    }

    public function destroy(Round $round)
    {
        // delete the article from the DB
        $round->delete();
        // use a 204 code as there is no content in the response
        return response(null, 204);
    }

    public function update(Request $request, Tournament $tournament, Round $round)
    {

        $data = $request->all();

        foreach ($data["currentRound"]["games"] as $game) {
            // Update the last game and players            
            DB::table('games')
                ->where('id', $game["id"])
                ->update(['complete' => 1]);

            DB::table('players')
                ->where('id', $game["players"][0]["id"])
                ->update([
                    'name' => $game["players"][0]["name"],
                    'score' => $game["players"][0]["score"],
                    'won' => $game["players"][0]["won"]
                ]);

            DB::table('players')
                ->where('id', $game["players"][1]["id"])
                ->update([
                    'name' => $game["players"][1]["name"],
                    'score' => $game["players"][1]["score"],
                    'won' => $game["players"][1]["won"]
                ]);
        }

        $round->fill($data)->save();

        $round->update(["complete" => 1]);

        return new RoundResource($round);
    }
}
