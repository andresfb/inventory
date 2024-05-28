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

    public function updateUser(User $user, array $params): void
    {
        if (isset($params['email'])
            && $user->email !== $params['email']
            && $this->userExists(['email' => $params['email']]))
        {
            throw new \RuntimeException('Email already exists'. 400);
        }

        $user->update($params);

        CacheService::clearUserCache($user->id);
    }
}
