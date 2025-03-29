<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtAttempting
{
    public function handle(\Jekk0\JwtAuth\Events\JwtAttempting $event): void
    {
        Log::info("Guard $event->guard: Attempting to login with email {$event->credentials['email']}.");
    }
}
