<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AdminAuthController
{
    private const GUARD = 'jwt-admin';

    #[OA\Post(path: '/api/auth/admin/login', description: 'Get pairs of tokens by credentials', requestBody: new OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', example: 'admin@example.com'),
                new OA\Property(property: 'password', example: 'password')
            ]
        )
    ), tags: ['Admin'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Token pair response', content: new OA\JsonContent(
            properties: [
                new OA\Property('access', properties: [
                    new OA\Property('token'),
                    new OA\Property('expiredAt', type: 'int')
                ]),
                new OA\Property('refresh', properties: [
                    new OA\Property('token'),
                    new OA\Property('expiredAt', type: 'int')
                ]),
            ],
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'HTTP Unauthorized Exception',
            content: new OA\JsonContent(
                properties: [new OA\Property('message', example: 'Unauthenticated.')]
            )
        ),
    ])]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $tokens = auth(self::GUARD)->attemptOrFail($credentials);

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(path: '/api/auth/admin/refresh', description: 'Admin refresh token endpoint', requestBody: new OA\RequestBody(
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'token')]
        )
    ), tags: ['Admin'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Token response', content: new OA\JsonContent(
            properties: [
                new OA\Property('access', properties: [
                    new OA\Property('token'),
                    new OA\Property('expiredAt', type: 'int')
                ]),
                new OA\Property('refresh', properties: [
                    new OA\Property('token'),
                    new OA\Property('expiredAt', type: 'int')
                ]),
            ],
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'HTTP Unauthorized Exception',
            content: new OA\JsonContent(
                properties: [new OA\Property('message', example: 'Unauthenticated.')]
            )
        ),
    ])]
    public function refresh(Request $request): JsonResponse
    {
        $tokens = auth(self::GUARD)->refreshTokens($request->get('token', ''));

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(path: '/api/auth/admin/logout', description: 'Invalidate access/refresh token pair for current session', security: [['JWT' => []]], tags: ['Admin'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Empty response', content: new OA\JsonContent(
            properties: []
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'HTTP Unauthorized Exception',
            content: new OA\JsonContent(
                properties: [new OA\Property('message', example: 'Unauthenticated.')]
            )
        ),
    ])]
    public function logout(): JsonResponse
    {
        auth(self::GUARD)->logout();

        return new JsonResponse();
    }

    #[OA\Post(path: '/api/auth/admin/logout/all', description: 'Invalidate access/refresh token pairs for all user sessions', security: [['JWT' => []]], tags: ['Admin'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Empty response', content: new OA\JsonContent(
            properties: []
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'HTTP Unauthorized Exception',
            content: new OA\JsonContent(
                properties: [new OA\Property('message', example: 'Unauthenticated.')]
            )
        ),
    ])]
    public function logoutFromAllDevices(): JsonResponse
    {
        auth(self::GUARD)->logoutFromAllDevices();

        return new JsonResponse();
    }

    #[OA\Get(path: '/api/auth/admin/profile', description: 'Get user profile endpoint', security: [['JWT' => []]], tags: ['Admin'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Token response', content: new OA\JsonContent(
            properties: [
                new OA\Property('name'),
                new OA\Property('email')
            ]
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'HTTP Unauthorized Exception',
            content: new OA\JsonContent(
                properties: [new OA\Property('message', example: 'Unauthenticated.')]
            )
        ),
    ])]
    public function profile(Request $request): JsonResponse
    {
        return new JsonResponse(['name' => $request->user()->name, 'email' => $request->user()->email]);
    }
}
