<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyAuthController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $tokens = auth('jwt-company')->attemptOrFail($credentials);

        return new JsonResponse($tokens->toArray());
    }

    public function refresh(Request $request): JsonResponse
    {
        $tokens = auth('jwt-company')->refreshTokens($request->get('token'));

        return new JsonResponse($tokens->toArray());
    }

    public function logout(): JsonResponse
    {
        auth('jwt-company')->logout();

        return new JsonResponse();
    }

    public function logoutFromAllDevices(): JsonResponse
    {
        auth('jwt-company')->logoutFromAllDevices();

        return new JsonResponse();
    }

    public function profile(Request $request): JsonResponse
    {
        return new JsonResponse(['name' => $request->user()->name, 'email' => $request->user()->email]);
    }
}
