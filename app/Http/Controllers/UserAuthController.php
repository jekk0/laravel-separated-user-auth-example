<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jekk0\JwtAuth\Contracts\RequestGuard;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "User Authentication", description: "Endpoints for user authentication")]
class UserAuthController
{
    private const GUARD_NAME = 'jwt-user';

    private readonly RequestGuard $guard;

    public function __construct()
    {
        $this->guard = auth(self::GUARD_NAME);
    }

    #[OA\Post(
        path: "/api/auth/user/login",
        description: "Authenticate user and return JWT tokens",
        summary: "User login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        tags: ["User Authentication"],
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
        path: '/api/auth/user/refresh',
        description: 'Refresh user authentication token',
        summary: "Refresh JWT token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [new OA\Property(property: 'token')]
            )
        ),
        tags: ['User Authentication'],
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
        path: '/api/auth/user/logout',
        description: 'Invalidate access/refresh token pair for current session',
        summary: "User logout",
        security: [['JWT' => []]],
        tags: ['User Authentication'],
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
        path: '/api/auth/user/logout/all',
        description: 'Invalidate access/refresh token pairs for all user sessions',
        summary: "Logout from all devices",
        security: [['JWT' => []]],
        tags: ['User Authentication'],
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
        path: '/api/auth/user/profile',
        description: 'Returns authenticated user\'s profile information',
        summary: "Get user profile",
        security: [['JWT' => []]],
        tags: ['User Authentication'],
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
        /** @var User $user */
        $user = $request->user();

        return new JsonResponse(['name' => $user->name, 'email' => $user->email]);
    }
}
