<?php

namespace App\Http\Filters\V1;

use App\Http\Validators\V1\CategoryValidator;
use App\Traits\IdentityFilterable;
use App\Traits\UserFilterable;

class CategoryFilter extends QueryFilter
{
    use IdentityFilterable;
    use UserFilterable;

    public function __construct(CategoryValidator $validator)
    {
        parent::__construct($validator);
    }
}
