<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Jekk0\JwtAuth\Contracts\TokenExtractor;

class CustomJwtTokenExtractor extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TokenExtractor::class, function () {
            return new class implements TokenExtractor {
                public function __invoke(Request $request): ?string
                {
                    return $request->bearerToken();
                }
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
