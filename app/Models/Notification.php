<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\NotificationType;
use App\Enums\NotificationStatus;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'data',
        'type',
        'status',
        'send_at',
        'attempts',
        'hash',
        'message',
        'error',
    ];

    protected $casts = [
        'data' => 'array',
        'type' => NotificationType::class,
        'status' => NotificationStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
