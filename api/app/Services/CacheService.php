<?php

namespace App\Services;

use App\Helpers\CategoriesCacheKeys;
use App\Helpers\ItemsCacheKeys;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function clearUserCache(int $userId): void
    {
        foreach (CategoriesCacheKeys::getAllKeys($userId) as $key) {
            Cache::tags($key)->flush();
        }

        foreach (ItemsCacheKeys::getAllKeys($userId) as $key) {
            Cache::tags($key)->flush();
        }
    }
}
