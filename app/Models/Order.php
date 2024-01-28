<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Filters\Filterable;
use App\Models\Relations\OrderRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use OrderRelation;
    use Filterable;

    protected $fillable = [
        'user_id',
        'nation_code',
        'amount',
        'status'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];
}
