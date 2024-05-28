<?php

namespace App\Validators\V1;

use App\Models\Category;

class CategoryValidator extends RequestValidator
{
    public function cacheKey(string $baseKey): string
    {
        if (empty($this->validated())) {
            throw new \RuntimeException('Validation failed');
        }

        return sprintf(
            '%s:%s:%s:%s',
            $baseKey,
            $this->validated['page'] ?? 1,
            $this->includesString(),
            $this->perPage,
        );
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function loadRelationships(): void
    {
        $this->relationshipList = Category::listRelationships();
    }
}
