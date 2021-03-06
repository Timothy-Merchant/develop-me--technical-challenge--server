<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            "round_id" => $this->round_id,
            "deuce" => $this->deuce,
            "complete" => $this->complete,
            "service" => $this->service,
            "players" => $this->players
        ];
    }
}
