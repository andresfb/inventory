<?php

namespace App\Http\Validators\V1;

use App\Helpers\ItemsCacheKeys;
use App\Models\Item;
use Exception;

class ItemValidator extends RequestValidator
{
    /**
     * @throws Exception
     */
    public function cacheKey(): string
    {
        return $this->baseCacheKey(
            ItemsCacheKeys::itemsByUserList($this->userId()),
        );
    }

    public function tagCacheKey(): string
    {
        return ItemsCacheKeys::itemsByUserList($this->userId());
    }

    protected function getRules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    protected function setRelationships(): void
    {
        $this->relationshipList = Item::listRelationships();
    }
}
