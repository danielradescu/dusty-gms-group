<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VerifiedAndUnblocked implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNotNull('email_verified_at')
            ->where('is_blocked', false);
    }
}
