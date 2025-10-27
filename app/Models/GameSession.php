<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\GameSessionType;

class GameSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'min_players',
        'max_players',
        'event_type_id',
    ];

    protected $casts = [
        'complexity' => 'decimal:2',
        'type' => GameSessionType::class,
    ];

}
