<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtAuthenticated
{
    public function handle(\Jekk0\JwtAuth\Events\JwtAuthenticated $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} authenticated.");
    }
}
