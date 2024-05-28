<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait UserFilterable
{
    public function userId(int $userId): Builder
    {
        return $this->builder->where('user_id', $userId);
    }
}
