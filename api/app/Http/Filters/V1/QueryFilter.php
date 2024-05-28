<?php

namespace App\Http\Filters\V1;

use App\Http\Validators\V1\RequestValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Builder $builder;

    public function __construct(public readonly RequestValidator $validator)
    {
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->validator->validated() as $key => $value) {
            $method = Str::camel($key);
            if (!method_exists($this, $method)) {
                continue;
            }

            $this->$method($value);
        }

        return $this->includeRelationships();
    }

    private function includeRelationships(): Builder
    {
        foreach ($this->validator->includes() as $include) {
            $this->builder->with($include);
        }

        return $this->builder;
    }
}
