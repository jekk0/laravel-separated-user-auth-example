<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class JwtValidated
{
    public function handle(\Jekk0\JwtAuth\Events\JwtValidated $event): void
    {
        Log::info("Guard $event->guard: User with email {$event->user->email} validated.");
    }
}
