<?php

namespace App\Http\Controllers;

use App\Models\Round;
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

    public function store(Request $request)
    {
        // get all the request data
        // returns an array of all the data the user sent
        $data = $request->all();
        // create round with data and store in DB
        // and return it as JSON
        // automatically gets 201 status as it's a POST request
        return Round::create($data);
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
