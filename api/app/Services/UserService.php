<?php

namespace App\Services;

use App\Models\User;
use App\Traits\Fieldable;

class UserService
{
    use Fieldable;

    public function userExists(array $args): bool
    {
        $query = User::query();
        foreach ($args as $field => $value) {
            $query->where($field, $value);
        }

        return $query->exists();
    }

    public function getUser(int $userId, array $params = []): User
    {
        $query = User::where('id', $userId);

        if (!empty($params[$this->includeField()])) {
            foreach ($params[$this->includeField()] as $include) {
                $query->with($include);
            }
        }

        $user = $query->first();
        if ($user === null) {
            throw new \RuntimeException('User not found', 404);
        }

        return $user;
    }

    public function updateUser(int $userId, array $params): void
    {
        $user = $this->getUser($userId);

        if (isset($params['email']) && $this->userExists(['email' => $params['email']])) {
            throw new \RuntimeException('Email already exists'. 400);
        }

        $user->update($params);

        CacheService::clearUserCache($userId);
    }
}
