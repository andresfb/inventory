<?php

namespace App\Helpers;

class ItemsCacheKeys
{
    public static array $baseKeys = [
        'item_by_user' => 'ITEM:BY:USER:%s',
        'items_by_user' => 'ITEM:BY:USER:%s',
    ];

    public static function itemByUser(int $userId): string
    {
        return sprintf(self::$baseKeys['item_by_user'], $userId);
    }

    public static function itemsByUserList(int $userId): string
    {
        return sprintf(self::$baseKeys['items_by_user'], $userId);
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
