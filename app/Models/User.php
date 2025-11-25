<?php

namespace App\Models;


use App\Enums\Role;
use App\Models\Scopes\VerifiedUnblockedAndRevieved;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Notifiable, HasRoles;

    protected static function booted(): void
    {
        static::addGlobalScope(new VerifiedUnblockedAndRevieved());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone_number',
        'info',
        'role',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function getPhotoURL()
    {
        if (is_null($this->photo))
            return Storage::disk('random_profile_photo')->url('profile' . $this->id % 10 . '.jpg');
        else
            return Storage::disk('profile_photo')->url($this->photo);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function gameSessionRequests(): HasMany
    {
        return $this->hasMany(GameSessionRequest::class);
    }

    public function notificationSubscription(): HasMany
    {
        return $this->hasMany(NotificationSubscription::class);
    }

    public function inAppNotifications()
    {
        return $this->hasMany(\App\Models\InAppNotification::class);
    }


    /**
     * Control where mail notifications are sent — or block them entirely.
     */
    public function routeNotificationForMail($notification)
    {
        // If user disabled all notifications, block delivery
        if ($this->notifications_disabled) {
            return null; // Returning null stops Laravel from sending any mail
        }

        // Otherwise, return their email address as usual
        return $this->email;
    }

    public function invites(): HasMany
    {
        return $this->hasMany(JoinRequest::class, 'initiated_by', 'id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isReviewed()
    {
        return !empty($this->reviewed_by);
    }
}
