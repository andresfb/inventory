<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ApiResponses;
    use RegistersUsers;

    protected function registered(Request $request, $user): JsonResponse
    {
        return $this->ok(
            message: 'Registered',
            data: [
                'token' => $user->createToken("API Token for $user->email")->plainTextToken,
            ]
        );
    }
}
