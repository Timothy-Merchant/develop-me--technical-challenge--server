<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return $games;
    }

    public function show(Game $game)
    {
        // just return the game
        return $game;
    }

    public function store(Request $request)
    {
        // get all the request data
        // returns an array of all the data the user sent
        $data = $request->all();
        // create game with data and store in DB
        // and return it as JSON
        // automatically gets 201 status as it's a POST request
        return Game::create($data);
    }

    public function destroy(Game $game)
    {
        // delete the article from the DB
        $game->delete();
        // use a 204 code as there is no content in the response
        return response(null, 204);
    }

    public function update(Request $request, Game $game)
    {
        // get the request data
        $data = $request->all();
        // update the game using the fill method
        // then save it to the database
        $game->fill($data)->save();
        // return the updated version
        return $game;
    }
}
