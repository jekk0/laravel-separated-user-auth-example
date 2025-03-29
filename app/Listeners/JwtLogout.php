<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtLogout
{
    public function handle(\Jekk0\JwtAuth\Events\JwtLogout $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} logged out.");
    }
}
