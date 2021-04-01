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
        // get all the request data
        // returns an array of all the data the user sent
        $data = $request->all();
        // create tournament with data and store in DB
        // and return it as JSON
        // automatically gets 201 status as it's a POST request
        return Tournament::create($data);
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
