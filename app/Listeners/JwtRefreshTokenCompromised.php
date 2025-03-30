<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtRefreshTokenCompromised
{
    public function handle(\Jekk0\JwtAuth\Events\JwtRefreshTokenCompromised $event): void
    {
        Log::info("Guard $event->guard: Refresh token compromised.");

    }
}
