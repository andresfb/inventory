<?php

namespace App\Services;

use App\Helpers\CategoriesCacheKeys;
use App\Models\Property;
use App\Models\Category;
use App\Traits\CacheTimeLivable;
use App\Traits\Fieldable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    use CacheTimeLivable;
    use Fieldable;

    /**
     * @noinspection PhpParamsInspection
     * @noinspection UnknownInspectionInspection
     */
    public function getCategory(int $categoryId, int $userId, array $params = []): ?Category
    {
        $includes = $params[$this->includeField()] ?? [];

        $key = CategoriesCacheKeys::categoryByUser($userId);

        return Cache::tags($key)
            ->remember(
                md5("$key:$categoryId:$userId"),
                $this->mediumLivedTtlMinutes(),
                function () use ($userId, $categoryId, $includes) {
                    $query = Category::where('id', $categoryId)
                        ->where('user_id', $userId);

                    $this->includeRelationships($includes, $query);

                    return $query->first();
                }
            );
    }

    public function getList(int $userId, array $params, int $perPage): LengthAwarePaginator
    {
        $includes = $params[$this->includeField()] ?? [];

        $cacheKey = sprintf(
            '%s:%s:%s:%s',
            CategoriesCacheKeys::categoriesByUserList($userId),
            $params['page'] ?? 1,
            $includes ? implode(',', $includes) : '',
            $perPage,
        );

        return Cache::tags(CategoriesCacheKeys::categoriesByUserList($userId))
            ->remember(
                md5($cacheKey),
                $this->mediumLivedTtlMinutes(),
                function () use ($userId, $perPage, $includes) {
                    $query = Category::where('user_id', $userId);

                    $this->includeRelationships($includes, $query);

                    return $query->paginate($perPage);
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

    private function includeRelationships(array $includes, Builder $query): void
    {
        foreach ($includes as $include) {
            $query->with($include);
        }
    }
}
