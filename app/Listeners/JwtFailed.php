<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtFailed
{
    public function handle(\Jekk0\JwtAuth\Events\JwtFailed $event): void
    {
        Log::info("Guard $event->guard: Login with email {$event->credentials['email']} failed.");
    }
}
