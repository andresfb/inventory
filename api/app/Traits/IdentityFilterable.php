<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IdentityFilterable
{
    public function identifier(int $identifier): Builder
    {
        return $this->builder->where('id', $identifier);
    }
}
