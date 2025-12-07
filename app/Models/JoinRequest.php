<?php

namespace App\Models;

use App\Enums\JoinRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class JoinRequest extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     */
    protected $table = 'join_requests';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'other_means_of_contact',
        'message',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Always generate a unique invitation token
            if (empty($model->invitation_token)) {
                $model->invitation_token = (string) Str::uuid();
            }

            // Set default status if not already defined
            if (empty($model->status)) {
                $model->status = JoinRequestStatus::PENDING;
            }

            // Automatically capture IP and User-Agent if running in a request context
            if (request()?->ip()) {
                $model->ip_address = $model->ip_address ?? request()->ip();
                $model->user_agent = $model->user_agent ?? request()->userAgent();
            }
        });
    }

    /**
     * Cast attributes.
     */
    protected $casts = [
        'status' => JoinRequestStatus::class,
        'reviewed_at' => 'datetime',
        'invitation_used_at' => 'datetime',
    ];

    /**
     * Initiator relationship (user who created or invited).
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    /**
     * Reviewer relationship (organizer/admin who reviewed it).
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Accessor for determining if request is approved.
     */
    protected function isApproved(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === JoinRequestStatus::APPROVED,
        );
    }

    /**
     * Accessor for determining if request is pending.
     */
    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === JoinRequestStatus::PENDING,
        );
    }

    /**
     * Accessor for determining if request is declined.
     */
    protected function isDeclined(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === JoinRequestStatus::REJECTED,
        );
    }

//    /**
//     * Accessor for determining if request is invited.
//     */
//    protected function isInvited(): Attribute
//    {
//        return Attribute::make(
//            get: fn() => $this->status === JoinRequestStatus::INVITED,
//        );
//    }

    /**
     * Scope: filter pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', JoinRequestStatus::PENDING);
    }

//    /**
//     * Scope: filter invited requests.
//     */
//    public function scopeInvited($query)
//    {
//        return $query->where('status', JoinRequestStatus::INVITED);
//    }

    /**
     * Scope: filter approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', JoinRequestStatus::APPROVED);
    }

    /**
     * Scope: filter declined requests.
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', JoinRequestStatus::REJECTED);
    }
}
