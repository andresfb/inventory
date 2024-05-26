<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Auth\Authenticatable;

class CategoryService
{
    public function seedCategories(?Authenticatable $user): void
    {
        if ($user === null) {
            return;
        }

        foreach (config('categories.base_categories') as $item) {
            // TODO: create and seed the attributes
            Category::create([
                'user_id' => $user->getAuthIdentifier(),
                'name' => $item,
            ]);
        }
    }
}
