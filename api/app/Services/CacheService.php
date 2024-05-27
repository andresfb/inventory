<?php

namespace App\Services;

use App\Helpers\CategoriesCacheKeys;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function clearUserCache(int $userId): void
    {
        foreach (CategoriesCacheKeys::getAllKeys($userId) as $key) {
            Cache::tags($key)->flush();
        }
    }
}
