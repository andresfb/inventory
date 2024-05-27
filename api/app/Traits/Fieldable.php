<?php

namespace App\Traits;

trait Fieldable
{
    public function includeField(): string
    {
        return config('constants.include_field');
    }
}
