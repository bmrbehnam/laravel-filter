<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Assign filter class to model for apply filters.
     *
     * @param Builder $builder Builder.
     * @param string $filterClass
     * @return Builder
     */
    public function scopeFilter(Builder $builder, string $filterClass): Builder
    {
        if (!is_subclass_of($filterClass, Filter::class)) {
            throw new \InvalidArgumentException('Invalid filter class provided');
        }

        return app($filterClass)->apply($builder);
    }
}
