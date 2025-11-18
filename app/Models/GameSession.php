<?php

namespace App\Models;

use App\Enums\GameComplexity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\GameSessionType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'min_players',
        'max_players',
        'event_type_id',
        'complexity',
        'organized_by',
        'type',
        'delay_until',
        'location',
        'start_at',
        'name',
    ];

    protected $casts = [
        'complexity' => GameComplexity::class,
        'type' => GameSessionType::class,
        'start_at' => 'datetime',
        'delay_until' => 'datetime',

    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organized_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function registration(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function hasOpenPositions()
    {
        return is_null($this->max_players) || ($this->max_players > $this->registration()->count());
    }

}
