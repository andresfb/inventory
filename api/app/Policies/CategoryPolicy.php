<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->categories !== null;
    }

    public function view(User $user, Category $category): bool
    {
        return $category->user->is($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Category $category): bool
    {
        return $category->user->is($user);
    }

    public function delete(User $user, Category $category): bool
    {
        return $category->user->is($user);
    }

    public function restore(User $user, Category $category): bool
    {
        return $category->user->is($user);
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $category->user->is($user);
    }
}
