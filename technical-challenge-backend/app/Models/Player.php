<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'score',
        'won'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
