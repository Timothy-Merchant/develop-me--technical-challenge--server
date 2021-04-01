<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'deuce',
        'complete',
        'service'
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function round()
    {     
        return $this->belongsTo(Round::class);
    }
}
