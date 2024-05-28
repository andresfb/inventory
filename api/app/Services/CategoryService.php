<?php

namespace App\Services;

use App\Helpers\CategoriesCacheKeys;
use App\Models\Property;
use App\Models\Category;
use App\Traits\CacheTimeLivable;
use App\Traits\Fieldable;
use App\Traits\Relatable;
use App\Validators\V1\CategoryValidator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    use CacheTimeLivable;
    use Fieldable;
    use Relatable;

    /**
     * @noinspection PhpParamsInspection
     * @noinspection UnknownInspectionInspection
     */
    public function getCategory(int $categoryId, CategoryValidator $validator): ?Category
    {
        $validator->validate();
        $key = CategoriesCacheKeys::categoryByUser($validator->userId());

        return Cache::tags($key)
            ->remember(
                md5("$key:$categoryId:{$validator->userId()}"),
                $this->mediumLivedTtlMinutes(),
                function () use ($validator, $categoryId) {
                    $query = Category::where('id', $categoryId)
                        ->where('user_id', $validator->userId());

                    $this->includeRelationships($validator->includes(), $query);

                    return $query->first();
                }
            );
    }

    /**
     * @noinspection PhpParamsInspection
     * @noinspection UnknownInspectionInspection
     */
    public function getList(CategoryValidator $validator): LengthAwarePaginator
    {
        $validator->validate();
        $tagKey = CategoriesCacheKeys::categoriesByUserList($validator->userId());
        $cacheKey = $validator->cacheKey($tagKey);

        return Cache::tags($tagKey)
            ->remember(
                md5($cacheKey),
                $this->mediumLivedTtlMinutes(),
                function () use ($validator) {
                    $query = Category::where('user_id', $validator->userId());

                    $this->includeRelationships($validator->includes(), $query);

                    return $query->paginate($validator->perPage());
                }
        );
    }

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
