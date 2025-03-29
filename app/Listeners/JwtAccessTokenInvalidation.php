<?php

namespace App\Listeners;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Jekk0\JwtAuth\Events\JwtAccessTokenDecoded;
use Jekk0\JwtAuth\Model\JwtRefreshToken;

class JwtAccessTokenInvalidation
{

    public function handle(JwtAccessTokenDecoded $event): void
    {
        Log::info("Guard $event->guard: Access token invalidation.");

        // Solution 1
        $accessTokenId = $event->accessToken->payload->getJwtId();
        $refreshToken = JwtRefreshToken::whereAccessTokenJti($accessTokenId)->first();

        if ($refreshToken === null) {
            Log::warning("Guard $event->guard: Access token invalid.");
            throw new AuthenticationException();
        }

        // Solution 2
        // $refreshTokenId = $event->accessToken->payload->getReferenceTokenId();
        // $refreshToken = JwtRefreshToken::find($refreshTokenId);
        //
        // if ($refreshToken === null) {
        //     throw new AuthenticationException();
        // }

        // Solution 3
        // If you do not want to use a relational database, you can implement token invalidation using two events:
        // 1. On Logout (JwtLogout Event) – Store the access token in a blacklist for its remaining lifetime using a fast storage solution, such as Redis or MongoDB.
        // 2. On Token Decoding (JwtAccessTokenDecoded Event) – Check whether the token is in the blacklist before processing it.
    }
}
