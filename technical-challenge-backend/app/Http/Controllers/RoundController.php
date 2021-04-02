<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function index()
    {
        $rounds = Round::all();
        return $rounds;
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

    public function update(Request $request, Round $round)
    {
        // get the request data
        $data = $request->all();
        // update the round using the fill method
        // then save it to the database
        $round->fill($data)->save();
        // return the updated version
        return $round;
    }
}
