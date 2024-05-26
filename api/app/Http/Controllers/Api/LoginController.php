<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ApiResponses;
    use AuthenticatesUsers;

    protected function sendLoginResponse(Request $request): JsonResponse
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $this->ok(
            message: 'Authenticated',
            data: [
                'token' => $this->guard()->user()?->createToken(
                    name: "API Token for {$this->guard()->user()->email}",
                    abilities: ['*'],
                    expiresAt: now()->addMonth()
                )->plainTextToken,
            ]
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $this->ok('');
    }

    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ]);
    }
}
