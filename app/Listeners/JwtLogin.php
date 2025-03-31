<?php

namespace App\Listeners;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class JwtLogin
{
    public function handle(\Jekk0\JwtAuth\Events\JwtLogin $event): void
    {
        /** @var Admin|Company|User $user */
        $user = $event->user;

        Log::info("Guard $event->guard: User with email {$user->email} logged.");
    }
}
