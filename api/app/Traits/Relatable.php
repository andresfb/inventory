<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Relatable
{
    // TODO: change this to a Query Scope
    protected function includeRelationships(array $includes, Builder $query): void
    {
        foreach ($includes as $include) {
            $query->with($include);
        }
    }
}
