<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class IncludeRelationshipRule implements ValidationRule
{
    public function __construct(private array $relationships)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        if (empty($this->relationships)) {
            return;
        }

        $relationships = str($value)->lower()
            ->replace(' ', '')
            ->explode(',');

        if ($relationships->isEmpty()) {
            return;
        }

        $common = array_intersect($relationships->toArray(), $this->relationships);
        if (count($common) > 0) {
            return;
        }

        $fail("The $attribute must be at least one these relationships." . implode(", ", $this->relationships));
    }
}
