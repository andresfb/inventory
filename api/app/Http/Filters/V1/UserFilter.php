<?php

namespace App\Http\Filters\V1;

use App\Http\Validators\V1\UserValidator;
use App\Traits\IdentityFilterable;

class UserFilter extends QueryFilter
{
    use IdentityFilterable;

    public function __construct(UserValidator $validator)
    {
        parent::__construct($validator);
    }
}
