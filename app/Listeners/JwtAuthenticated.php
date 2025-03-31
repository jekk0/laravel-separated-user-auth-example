<?php

namespace App\Listeners;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class JwtAuthenticated
{
    public function handle(\Jekk0\JwtAuth\Events\JwtAuthenticated $event): void
    {
        /** @var Admin|Company|User $user */
        $user = $event->user;

        Log::info("Guard $event->guard: User with email {$user->email} authenticated.");
    }
}
