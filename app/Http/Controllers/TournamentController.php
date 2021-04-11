<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Http\Resources\GameResource;
use App\Http\Resources\RoundResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\TournamentResource;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::orderBy('updated_at', 'desc')->get()->take(50);

        $tournamentResources = $tournaments->map(function ($tournament) {
            return new TournamentResource($tournament);
        })->all();

        return [$tournamentResources];
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

            array_push($newRounds, new RoundResource($newRound));

            foreach ($round["games"] as $game) {
                $newGame = Game::create([
                    "round_id" => $newRound["id"],
                    "deuce" => 0,
                    "service" => 0,
                    "complete" => 0
                ])->round()->associate($newRound);

                array_push($newGames, new GameResource($newGame));

                $newPlayer1 = Player::create([
                    "game_id" => $newGame["id"],
                    "name" => isset($game["player1"]["name"]) ? $game["player1"]["name"] : "",
                    "score" => 0,
                    "won" => 0,
                ])->game()->associate($newGame);

                array_push($newPlayers, new PlayerResource($newPlayer1));

                $newPlayer2 = Player::create([
                    "game_id" => $newGame["id"],
                    "name" => isset($game["player2"]["name"]) ? $game["player2"]["name"] : "",
                    "score" => 0,
                    "won" => 0,
                ])->game()->associate($newGame);

                array_push($newPlayers, new PlayerResource($newPlayer2));
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

    public function endTournament(Request $request, Tournament $tournament)
    {
        // get the request data
        $data = $request->all();

        $players = $data["games"][0]["players"];

        $data["champion"] = $players[0]["won"] === 1 ? $players[0]["name"] : $players[1]["name"];

        // update the tournament using the fill method
        // then save it to the database
        $tournament->fill(["champion" => $data["champion"]])->save();

        // return the updated version
        return [$tournament, $data];
    }
}
