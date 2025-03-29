<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtLogoutFromAllDevices
{
    public function handle(\Jekk0\JwtAuth\Events\JwtLogoutFromAllDevices $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} logged out from all devices.");
    }
}
