<?php

namespace App\Helpers;

class CategoriesCacheKeys
{
    public static array $baseKeys = [
        'category_by_user' => 'CATEGORY:BY:USER:%s',
        'categories_by_user' => 'CATEGORIES:BY:USER:%s',
    ];

    public static function categoryByUser(int $userId): string
    {
        return sprintf(self::$baseKeys['category_by_user'], $userId);
    }

    public static function categoriesByUserList(int $userId): string
    {
        return sprintf(self::$baseKeys['categories_by_user'], $userId);
    }

    public static function getAllKeys(int $userId): array
    {
        $keys = [];
        foreach (self::$baseKeys as $key => $value) {
            $keys[] = sprintf($key, $userId);
        }

        return $keys;
    }
}
