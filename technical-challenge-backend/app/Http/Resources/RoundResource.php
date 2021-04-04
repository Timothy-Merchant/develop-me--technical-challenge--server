<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "complete" => $this->complete,
            "tournament_id" => $this->tournament_id,
            "games" => $this->games->map(function ($game, $key) {
                return new GameResource(($game));
            })
        ];
    }
}
