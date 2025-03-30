<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Jekk0\JwtAuth\Model\JwtRefreshToken;

class JwtRefreshTokenRevocation
{
    public function handle(\Jekk0\JwtAuth\Events\JwtRefreshTokenCompromised $event): void
    {
        Log::info("Guard $event->guard: Refresh token revocation.");

        // Get all user refresh tokens
        $affectedRefreshTokens = JwtRefreshToken::where('sub', '=', (string)$event->user->id)->get();

        // If you use Access token invalidation then this step is not needed
        foreach ($affectedRefreshTokens as $refreshToken) {
            $accessTokenId = $refreshToken->access_token_jti;

            // Invalidate access tokens
            // ...
        }

        // Invalidate refresh tokens related to user
        JwtRefreshToken::whereIn('jti', $affectedRefreshTokens->pluck('jti'))->delete();

        // Send notification to user
        //...
    }
}
