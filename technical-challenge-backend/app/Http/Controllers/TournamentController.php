<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::all();
        return $tournaments;
    }

    public function show(Tournament $tournament)
    {
        // just return the tournament
        return $tournament;
    }

    public function startTournament(Request $request)
    {
        $data = $request->all();

        $rounds = $data["rounds"];

        $newTournament = Tournament::create(['champion' => '']);
        $tournamentID = $newTournament["id"];

        $newRounds = [];
        $newGames = [];
        $newPlayers = [];

        foreach ($rounds as $round) {
            $newRound = Round::create([
                'complete' => 0,
                'tournament_id' => $tournamentID
            ])->tournament()->associate($newTournament);

            array_push($newRounds, $newRound);

            foreach ($round["games"] as $game) {
                $newGame = Game::create([
                    "round_id" => $newRound["id"],
                    "deuce" => 0,
                    "service" => 0,
                    "complete" => 0
                ])->round()->associate($newRound);

                array_push($newGames, $newGame);

                $newPlayer1 = Player::create([
                    "game_id" => $newGame["id"],
                    "name" => isset($game["player1"]["name"]) ? $game["player1"]["name"] : "",
                    "score" => 0,
                    "won" => 0,
                ])->game()->associate($newGame);

                array_push($newPlayers, $newPlayer1);

                $newPlayer2 = Player::create([
                    "game_id" => $newGame["id"],
                    "name" => isset($game["player2"]["name"]) ? $game["player2"]["name"] : "",
                    "score" => 0,
                    "won" => 0,
                ])->game()->associate($newGame);

                array_push($newPlayers, $newPlayer2);
            }
        }

        return [$newTournament, $newRounds, $newGames, $newPlayers];
    }

    public function destroy(Tournament $tournament)
    {
        // delete the article from the DB
        $tournament->delete();
        // use a 204 code as there is no content in the response
        return response(null, 204);
    }

    public function update(Request $request, Tournament $tournament)
    {
        // get the request data
        $data = $request->all();
        // update the tournament using the fill method
        // then save it to the database
        $tournament->fill($data)->save();
        // return the updated version
        return $tournament;
    }
}
