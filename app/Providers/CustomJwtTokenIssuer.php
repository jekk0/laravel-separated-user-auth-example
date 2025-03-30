<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Jekk0\JwtAuth\Contracts\TokenIssuer;

class CustomJwtTokenIssuer extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TokenIssuer::class, function () {
            return new class () implements TokenIssuer {
                public function __invoke(Request $request): string
                {
                    return 'CustomIssuer';
                }
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
