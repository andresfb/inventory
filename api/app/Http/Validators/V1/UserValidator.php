<?php

namespace App\Http\Validators\V1;

use App\Models\User;

class UserValidator extends RequestValidator
{
    public function cacheKey(): string
    {
        return '';
    }

    public function tagCacheKey(): string
    {
        return '';
    }

    protected function getRules(): array
    {
        return [];
    }

    protected function setRelationships(): void
    {
        $this->relationshipList = User::listRelationships();
    }
}
