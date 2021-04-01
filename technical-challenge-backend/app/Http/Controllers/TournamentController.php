<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        // The request will be an array of player objects

        // Reset score and won for players moving to next round
        // let players = newPlayers.map((player, index) => ({ ...player, won: 0, score: 0 }))

        // // Slice the array of names into two halves, then create new player objects using the passed in names
        // let half = Math.floor(players.length / 2)
        // let player1s = players.slice(0, half);
        // let player2s = players.slice(half, players.length);

        // // Create an array of new 'game' objects that contain two player objects for each competitor
        // let mergedGames = player1s.map((player1, index) => ({
        //     id: index,
        //     player1: player1,
        //     player2: player2s[index],
        //     deuce: 0,
        //     service: 1,
        //     complete: 0
        // }))

        // return mergedGames

        $newTournament = Tournament::create(['champion' => '']);

        $newRounds = $newTournament->rounds()->createMany([
            ['complete' => 0],
            ['complete' => 0],
        ]);

        $newTournament->push();

        $newGames = $newRounds[0]->games()->createMany([
            ['deuce' => 0, 'complete' => 0, 'service' => 0],
            ['deuce' => 0, 'complete' => 0, 'service' => 0],
            ['deuce' => 0, 'complete' => 0, 'service' => 0]
        ]);

        $newRounds->push();

        $newPlayers = $newGames[0]->players()->createMany($request->all());

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
