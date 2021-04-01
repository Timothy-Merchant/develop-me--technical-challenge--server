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

        // To test, we're gonna try and make a tournament, with 1 round, with 1 game, with all the players in it

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

        $newPlayers = $newGames[0]->players()->createMany([
            ['name' => '', 'score' => 0, 'won' => 0],
            ['name' => '', 'score' => 0, 'won' => 0],
            ['name' => '', 'score' => 0, 'won' => 0]
        ]);

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
