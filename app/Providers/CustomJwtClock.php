<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jekk0\JwtAuth\Contracts\Clock;
use DateTimeImmutable;
use DateTimeZone;

class CustomJwtClock extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Clock::class, function () {
            return new class implements Clock {
                public function now(): DateTimeImmutable
                {
                    return new DateTimeImmutable('now', new DateTimeZone('UTC'));
                }
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
