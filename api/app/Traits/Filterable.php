<?php

namespace App\Traits;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    private QueryFilter $filters;

    public function scopeSetFilters(Builder $query, QueryFilter $filters): Builder
    {
        if (!empty($this->filters)) {
            return $query;
        }

        $this->filters = $filters;

        return $query;
    }

    public function scopeSetIdentifier(Builder $query, int $identifier): Builder
    {
        $this->filters->validator->setIdentifier($identifier);

        return $query;
    }

    public function scopeFilter(Builder $query): Builder
    {
        return $this->filters->apply($query);
    }
}
