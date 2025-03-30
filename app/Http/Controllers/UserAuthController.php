<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController
{
    private const GUARD = 'jwt-user';

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $tokens = auth(self::GUARD)->attemptOrFail($credentials);

        return new JsonResponse($tokens->toArray());
    }

    public function refresh(Request $request): JsonResponse
    {
        $tokens = auth(self::GUARD)->refreshTokens((string)$request->get('token'));

        return new JsonResponse($tokens->toArray());
    }

    public function logout(): JsonResponse
    {
        auth(self::GUARD)->logout();

        return new JsonResponse();
    }

    public function logoutFromAllDevices(): JsonResponse
    {
        auth(self::GUARD)->logoutFromAllDevices();

        return new JsonResponse();
    }

    public function profile(Request $request): JsonResponse
    {
        return new JsonResponse(['name' => $request->user()->name, 'email' => $request->user()->email]);
    }
}
