<?php

namespace App\Traits;

trait Fieldable
{
    protected function includeField(): string
    {
        return config('constants.include_field');
    }
}
