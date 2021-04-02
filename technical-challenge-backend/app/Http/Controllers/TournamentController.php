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

        $data = $request->all();

        $newTournament = Tournament::create(['champion' => '']);

        // $newGames = $newRounds[0]->games()->createMany($data["games"]);

        // $newRounds->push();

        // $newPlayers = $newGames[0]->players()->createMany($data["players"]);


        // $animal = new Animal($data);                 
        // $animal->owner()->associate($owner);
        // $animal->save();
        // $animal->setTreatments($request->get("treatments"));
        // return new AnimalResource($animal);

        // return [$newTournament, $newRounds, $newGames, $newPlayers];
        return $newTournament;
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
