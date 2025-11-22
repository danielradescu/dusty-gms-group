<?php

namespace App\Models;

use App\Enums\GameComplexity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\GameSessionStatus;
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
        'delay_until',
        'location',
        'start_at',
        'name',
    ];

    protected $casts = [
        'complexity' => GameComplexity::class,
        'status' => GameSessionStatus::class,
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

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function hasOpenPositions()
    {
        return is_null($this->max_players) || ($this->max_players > $this->registrations()->count());
    }

    public function isEditable(): bool
    {
        return (now() < $this->start_at)
            && in_array($this->status, [GameSessionStatus::RECRUITING_PARTICIPANTS, GameSessionStatus::CONFIRMED_BY_ORGANIZER]);
    }

}
