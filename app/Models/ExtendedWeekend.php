<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ExtendedWeekend extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'created_by',
        'comment',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function isInRange(Carbon $date): bool
    {
        return $date->between($this->start_date, $this->end_date);
    }

    public function user(): ?BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
