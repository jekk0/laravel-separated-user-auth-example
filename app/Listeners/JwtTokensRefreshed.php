<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtTokensRefreshed
{
    public function handle(\Jekk0\JwtAuth\Events\JwtTokensRefreshed $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} refreshed token pairs.");
    }
}
