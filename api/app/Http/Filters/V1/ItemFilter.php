<?php

namespace App\Http\Filters\V1;

use App\Http\Validators\V1\ItemValidator;
use App\Traits\IdentityFilterable;
use App\Traits\UserFilterable;
use Illuminate\Database\Eloquent\Builder;

class ItemFilter extends QueryFilter
{
    use IdentityFilterable;
    use UserFilterable;

    public function __construct(ItemValidator $validator)
    {
        parent::__construct($validator);
    }

    public function categoryId(int $categoryId): Builder
    {
        return $this->builder->where('category_id', $categoryId);
    }
}
