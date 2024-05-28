<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Contracts\Auth\Authenticatable;

class CategoryService
{
    public function seedCategories(?Authenticatable $user): void
    {
        if ($user === null) {
            return;
        }

        foreach (config('categories.base_categories') as $category) {
            $model = Category::create([
                'user_id' => $user->getAuthIdentifier(),
                'name' => $category,
            ]);

            if ($model === null) {
                continue;
            }

            if (!array_key_exists($category, config('categories.base_attributes'))) {
                continue;
            }

            foreach (config('categories.base_attributes')[$category] as $attribute) {
                Property::create([
                    'category_id' => $model->id,
                    'name' => $attribute,
                ]);
            }
        }
    }
}
