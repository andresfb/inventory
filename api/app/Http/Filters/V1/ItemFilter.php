<?php

namespace App\Http\Filters\V1;

use App\Http\Validators\V1\ItemValidator;
use App\Traits\DatesAtFilterable;
use App\Traits\IdentityFilterable;
use App\Traits\UserFilterable;
use Illuminate\Database\Eloquent\Builder;

class ItemFilter extends QueryFilter
{
    use IdentityFilterable;
    use UserFilterable;
    use DatesAtFilterable;

    public function __construct(ItemValidator $validator)
    {
        parent::__construct($validator);
    }

    public function categoryId(int $categoryId): Builder
    {
        return $this->builder->where('category_id', $categoryId);
    }

    public function name(string $name): Builder
    {
        return $this->builder->where('name', 'like', '%'.$name.'%');
    }

    public function notes(string $notes): Builder
    {
        return $this->builder->where('notes', 'like', '%'.$notes.'%');
    }

    public function tags(array $tags): Builder
    {
        return $this->builder->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('tags.id', $tags);
        });
    }


}
