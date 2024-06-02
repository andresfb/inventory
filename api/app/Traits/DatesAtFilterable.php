<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DatesAtFilterable
{
    public function createdAt(string $value): Builder
    {
        return $this->filterDate('created_at', $value);
    }

    public function updatedAt(string $value): Builder
    {
        return $this->filterDate('updated_at', $value);
    }

    public function purchasedAt(string $value): Builder
    {
        return $this->filterDate('purchased_at', $value);
    }

    private function filterDate(string $field, string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween($field, $dates);
        }

        return $this->builder->whereDate($field, $value);
    }
}
