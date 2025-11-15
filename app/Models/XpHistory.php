<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpHistory extends Model
{
    protected $table = 'xp_history';

    protected $fillable = [
        'user_id',
        'xp',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
