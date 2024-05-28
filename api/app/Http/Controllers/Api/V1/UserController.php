<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function show(UserFilter $filter, int $userId): JsonResponse|UserResource
    {
        try {
            if ((int) auth()->id() !== $userId) {
                return $this->error('Unauthorized', 401);
            }

            return new UserResource(
                User::setFilters($filter)
                    ->setIdentifier($userId)
                    ->filter()
                    ->firstOrFail()
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $this->userService->updateUser($user, $request->validated());

            return $this->ok('user updated successfully.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
