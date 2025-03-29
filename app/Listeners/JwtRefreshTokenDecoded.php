<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtRefreshTokenDecoded
{
    public function handle(\Jekk0\JwtAuth\Events\JwtRefreshTokenDecoded $event): void
    {
        Log::info("Guard $event->guard: Access token decoded {$event->refreshToken->payload->getJwtId()}.");
    }
}
