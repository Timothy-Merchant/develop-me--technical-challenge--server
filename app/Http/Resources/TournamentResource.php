<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
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
            "champion" => $this->champion,
            "rounds" => $this->rounds->map(function ($round, $key) {
                return new RoundResource(($round));
            }),
            "updated_at" => Carbon::createFromTimeString($this->updated_at)->format('F jS, Y')
        ];
    }
}
