<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    private Builder $builder;
    private array $values = [];

    // TODO: consider passing the Validators to this object so it can handle the entire process
    // TODO: if we do the adobe, how can the service access all the validated values before filtering?
    // TODO: should I forgo the service and move the entire process to the Model? How can I cache there?

    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    public function apply(Builder $builder): Builder
    {
        if (empty($this->values)) {
            throw new \RuntimeException('values not set');
        }

        $this->builder = $builder;

        foreach ($this->values as $key => $value) {
            if (!method_exists($this, $key)) {
                continue;
            }

            $this->$key($value);
        }

        return $this->builder;
    }
}
