<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jekk0\JwtAuth\Contracts\RequestGuard;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Admin Authentication", description: "Endpoints for admin user authentication")]
class AdminAuthController
{
    private const GUARD_NAME = 'jwt-admin';

    private RequestGuard $guard;

    public function __construct()
    {
        $this->guard = auth(self::GUARD_NAME);
    }

    #[OA\Post(
        path: "/api/auth/admin/login",
        description: "Authenticate admin user and return JWT tokens",
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
                response: Response::HTTP_OK,
                description: 'Token pair response',
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

        $tokens = $this->guard->attemptOrFail($credentials);

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(
        path: '/api/auth/admin/refresh',
        description: 'Refresh admin user authentication token',
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
        $tokens = $this->guard->refreshTokens((string)$request->get('token'));

        return new JsonResponse($tokens->toArray());
    }

    #[OA\Post(
        path: '/api/auth/admin/logout',
        description: 'Invalidate access/refresh token pair for current session',
        summary: "User logout",
        security: [['JWT' => []]],
        tags: ['Admin Authentication'],
        responses: [
        new OA\Response(
            response: Response::HTTP_OK,
            description: 'Empty response',
            content: new OA\JsonContent(
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
    ]
    )]
    public function logout(): JsonResponse
    {
        $this->guard->logout();

        return new JsonResponse();
    }

    #[OA\Post(
        path: '/api/auth/admin/logout/all',
        description: 'Invalidate access/refresh token pairs for all user sessions',
        summary: "Logout from all devices",
        security: [['JWT' => []]],
        tags: ['Admin Authentication'],
        responses: [
        new OA\Response(
            response: Response::HTTP_OK,
            description: 'Empty response',
            content: new OA\JsonContent(
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
    ]
    )]
    public function logoutFromAllDevices(): JsonResponse
    {
        $this->guard->logoutFromAllDevices();

        return new JsonResponse();
    }

    #[OA\Get(
        path: '/api/auth/admin/profile',
        description: 'Returns authenticated user\'s profile information',
        summary: "Get user profile",
        security: [['JWT' => []]],
        tags: ['Admin Authentication'],
        responses: [
        new OA\Response(
            response: Response::HTTP_OK,
            description: "User profile information",
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
    ]
    )]
    public function profile(Request $request): JsonResponse
    {
        /** @var Admin $user */
        $user = $request->user();

        return new JsonResponse(['name' => $user->name, 'email' => $user->email]);
    }
}
