<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Admin Authentication", description: "Endpoints for admin authentication")]
class AdminAuthController
{
    private const GUARD = 'jwt-admin';

    #[OA\Post(
        path: "/api/auth/admin/login",
        description: "Authenticate user and return JWT tokens",
        summary: "User login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        tags: ["Admin Authentication"],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK, description: 'Token pair response',
                content: new OA\JsonContent(
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
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [new OA\Property('message', example: 'Unauthenticated.')]
                )
            ),
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $tokens = auth(self::GUARD)->attemptOrFail($credentials);

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(
        path: '/api/auth/admin/refresh',
        description: 'Refresh admin authentication token',
        summary: "Refresh JWT token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'token')]
            )
        ),
        tags: ['Admin Authentication'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Token response',
                content: new OA\JsonContent(
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
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [new OA\Property('message', example: 'Unauthenticated.')]
                )
            ),
        ]
    )]
    public function refresh(Request $request): JsonResponse
    {
        $tokens = auth(self::GUARD)->refreshTokens((string)$request->get('token'));

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(path: '/api/auth/admin/logout', description: 'Invalidate access/refresh token pair for current session', summary: "User logout",
        security: [['JWT' => []]], tags: ['Admin Authentication'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Empty response', content: new OA\JsonContent(
            properties: []
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
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

    #[OA\Post(path: '/api/auth/admin/logout/all', description: 'Invalidate access/refresh token pairs for all user sessions',
        summary: "Logout from all devices",
        security: [['JWT' => []]], tags: ['Admin Authentication'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: 'Empty response', content: new OA\JsonContent(
            properties: []
        )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
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

    #[OA\Get(path: '/api/auth/admin/profile', description: 'Returns authenticated user\'s profile information', summary: "Get user profile", security: [['JWT' => []]], tags: ['Admin Authentication'], responses: [
        new OA\Response(
            response: Response::HTTP_OK, description: "User profile information",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com")
                ]
            )
        ),
        new OA\Response(
            response: Response::HTTP_UNAUTHORIZED,
            description: 'Unauthorized',
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
