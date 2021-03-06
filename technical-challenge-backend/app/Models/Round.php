<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'complete',
        'tournament_id'
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
