<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtLogin
{
    public function handle(\Jekk0\JwtAuth\Events\JwtLogin $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} logged.");
    }
}
