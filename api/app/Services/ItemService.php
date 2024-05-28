<?php

namespace App\Services;

use App\Helpers\ItemsCacheKeys;
use App\Models\Item;
use App\Traits\CacheTimeLivable;
use App\Traits\Fieldable;
use App\Traits\Relatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ItemService
{
    use CacheTimeLivable;
    use Fieldable;
    use Relatable;

    // TODO change the methods to use the RequestValidator and the QueryFilter

    public function getItem(int $itemId, int $userId, array $params = []): ?Item
    {
        $includes = $params[$this->includeField()] ?? [];

        $key = ItemsCacheKeys::itemByUser($userId);

        return Cache::tags($key)
            ->remember(
                md5("$key:$itemId:$userId"),
                $this->mediumLivedTtlMinutes(),
                function () use ($userId, $itemId, $includes) {
                    $query = Item::where('item_id', $itemId)
                        ->where('user_id', $userId)
                        ->withInfo();

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
            ItemsCacheKeys::itemsByUserList($userId),
            $params['page'] ?? 1,
            $includes ? implode(',', $includes) : '',
            $perPage,
        );

        return Cache::tags(ItemsCacheKeys::itemsByUserList($userId))
            ->remember(
                md5($cacheKey),
                $this->mediumLivedTtlMinutes(),
                function () use ($userId, $perPage, $includes) {
                    $query = Item::where('user_id', $userId)
                        ->withInfo();

                    $this->includeRelationships($includes, $query);

                    return $query->paginate($perPage);
                }
            );
    }
}
