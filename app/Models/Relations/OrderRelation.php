<?php

namespace App\Models\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait OrderRelation
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
