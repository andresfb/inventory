<?php

namespace App\Http\Validators\V1;

use App\Helpers\CategoriesCacheKeys;
use App\Models\Category;
use Exception;

class CategoryValidator extends RequestValidator
{
    /**
     * @throws Exception
     */
    public function cacheKey(): string
    {
        return $this->baseCacheKey(
            CategoriesCacheKeys::categoriesByUserList($this->userId())
        );
    }

    public function tagCacheKey(): string
    {
        return CategoriesCacheKeys::categoriesByUserList($this->userId());
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function setRelationships(): void
    {
        $this->relationshipList = Category::listRelationships();
    }
}
