<?php

namespace App\Filters;

use App\Exceptions\FilterException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

abstract class Filter
{
    /**
     * The filters to be applied.
     *
     * @var array
     */
    protected array $filters = [];

    /**
     * Custom filters that can be applied using specific methods.
     *
     * @var array
     */
    protected array $customFilters = [];

    /**
     * Mapping of filters to fields or methods.
     *
     * @var array
     */
    protected array $mapFilters = [];

    /**
     * Query builder instance.
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Request instance for receive filter data.
     *
     * @var Request
     */
    public function __construct(private readonly Request $request)
    {
        //
    }

    /**
     * Apply filters to the given builder instance.
     *
     * @param Builder $builder
     * @return Builder
     * @throws FilterException
     */
    public function apply(Builder $builder): Builder
    {
        try {
            $this->builder = $builder;

            $this->applyFilters();
            $this->applyCustomFilters();

            return $this->builder;
        } catch (Throwable $exception) {
            throw new FilterException($exception);
        }
    }

    /**
     * Apply filters to the builder.
     */
    private function applyFilters(): void
    {
        foreach ($this->getFilters() as $filterKey => $value) {
            $field = $this->mapFilters[$filterKey] ?? $filterKey;
            $operator = $this->filters[$filterKey];
            $value = $this->setValue($operator, $value);
            $this->addCondition($field, $operator, $value);
        }
    }

    /**
     * Apply custom filters to the builder using specified methods.
     */
    private function applyCustomFilters(): void
    {
        foreach ($this->getCustomFilters() as $customFilter => $customFilterValue) {
            $method = $this->mapFilters[$customFilter] ?? $customFilter;
            $method = Str::camel($method);

            // Check if the method exists before calling it.
            if (method_exists($this, $method)) {
                $this->$method($customFilterValue);
            }
        }
    }

    /**
     * Get filters from the request.
     *
     * @return array
     */
    private function getFilters(): array
    {
        return array_filter($this->request->only(array_keys($this->filters)), fn($item) => !is_null($item));
    }

    /**
     * Get custom filters from the request.
     *
     * @return array
     */
    private function getCustomFilters(): array
    {
        return array_filter($this->request->only($this->customFilters), fn($item) => !is_null($item));
    }

    /**
     * Modify the filter value based on the operator.
     *
     * @param string $operator
     * @param string $value
     * @return string
     */
    private function setValue(string $operator, string $value): string
    {
        return $operator === 'like' ? '%' . $value . '%' : $value;
    }

    /**
     * Add a condition to the query builder.
     *
     * @param string $field
     * @param string $operator
     * @param mixed $value
     */
    private function addCondition(string $field, string $operator, mixed $value): void
    {
        $this->builder->where($field, $operator, $value);
    }
}
