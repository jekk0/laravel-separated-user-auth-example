<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Jekk0\JwtAuth\Contracts\RequestGuard;
use Jekk0\JwtAuth\TokenPair;

abstract class TestCase extends BaseTestCase
{
    protected function loginAs(Authenticatable $authenticatable): TokenPair
    {
        $guardName = match ($authenticatable::class) {
            Admin::class => 'jwt-admin',
            Company::class => 'jwt-company',
            User::class => 'jwt-user',
            default => throw new \RuntimeException('Unsupported guard for user class ' . $authenticatable::class)
        };

        /** @var RequestGuard $guard */
        $guard = \auth($guardName);

        return $guard->login($authenticatable);
    }
}
