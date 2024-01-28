<?php

namespace App\Filters\V1;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends Filter
{
    protected array $filters = [
        'status' => '=',
        'nation_code' => 'like',
        'user' => '=',
    ];

    protected array $customFilters = [
        'min_amount',
        'max_amount',
        'customer_name'
    ];

    protected array $mapFilters = [
        'user' => 'user_id' // map that user is alias of the user_id field in table
    ];

    protected function minAmount(int $value): Builder
    {
        return $this->builder->where('amount', '>=', $value);
    }

    protected function maxAmount(int $value): Builder
    {
        return $this->builder->where('amount', '<=', $value);
    }

    protected function customerName(string $value): Builder
    {
        return $this->builder->whereHas('user', function ($q) use ($value) {
            $q->where('name', 'like', "%$value%");
        });
    }
}
