<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct(private readonly UserService $userService)
    {
        parent::__construct(User::listRelationships());
    }

    public function show(Request $request, int $userId): JsonResponse|UserResource
    {
        try {
            if ((int) auth()->id() !== $userId) {
                return $this->error('Unauthorized', 401);
            }

            [$values, ] = $this->getValues($request);

            return new UserResource(
                $this->userService->getUser($userId, $values)
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateUserRequest $request, int $userId): JsonResponse
    {
        try {
            if ((int) auth()->id() !== $userId) {
                return $this->error('Unauthorized', 401);
            }

            $this->userService->updateUser($userId, $request->validated());

            return $this->ok('user updated successfully.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
