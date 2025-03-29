<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtAccessTokenDecoded
{
    public function handle(\Jekk0\JwtAuth\Events\JwtAccessTokenDecoded $event): void
    {
        Log::info("Guard $event->guard: Access token decoded {$event->accessToken->payload->getJwtId()}.");
    }
}
