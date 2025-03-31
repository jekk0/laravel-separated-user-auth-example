<?php

namespace Tests\Feature;

use Database\Factories\AdminFactory;
use Database\Factories\CompanyFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Jekk0\JwtAuth\Contracts\TokenManager;
use Jekk0\JwtAuth\Events\JwtAccessTokenDecoded;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UserAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('userAccessProvider')]
    public function test_login(int $expectedCode, string $userClassFactory): void
    {
        $user = $userClassFactory::new()->create();

        $response = $this->postJson('/api/auth/user/login', ['email' => $user->email, 'password' => 'password']);

        self::assertSame($expectedCode, $response->getStatusCode());
    }

    #[DataProvider('userAccessProvider')]
    public function test_refresh(int $expectedCode, string $userClassFactory): void
    {
        $user = $userClassFactory::new()->create();
        $tokenPair = $this->loginAs($user);

        $response = $this->postJson('/api/auth/user/refresh', ['token' => $tokenPair->refresh->token]);

        self::assertSame($expectedCode, $response->getStatusCode());
    }

    #[DataProvider('userAccessProvider')]
    public function test_logout(int $expectedCode, string $userClassFactory): void
    {
        $user = $userClassFactory::new()->create();
        Event::forget(JwtAccessTokenDecoded::class);
        $tokenPair = $this->app->get(TokenManager::class)->makeTokenPair($user);

        $response = $this->postJson('/api/auth/user/logout', [], ['Authorization' => 'Bearer ' . $tokenPair->access->token]);

        self::assertSame($expectedCode, $response->getStatusCode());
    }

    #[DataProvider('userAccessProvider')]
    public function test_logout_from_all_devices(int $expectedCode, string $userClassFactory): void
    {
        $user = $userClassFactory::new()->create();
        Event::forget(JwtAccessTokenDecoded::class);
        $tokenPair = $this->app->get(TokenManager::class)->makeTokenPair($user);

        $response = $this->postJson('/api/auth/user/logout/all', [], ['Authorization' => 'Bearer ' . $tokenPair->access->token]);

        self::assertSame($expectedCode, $response->getStatusCode());
    }

    #[DataProvider('userAccessProvider')]
    public function test_profile(int $expectedCode, string $userClassFactory): void
    {
        $user = $userClassFactory::new()->create();
        Event::forget(JwtAccessTokenDecoded::class);
        $tokenPair = $this->app->get(TokenManager::class)->makeTokenPair($user);

        $response = $this->getJson('/api/auth/user/profile', ['Authorization' => 'Bearer ' . $tokenPair->access->token]);

        self::assertSame($expectedCode, $response->getStatusCode());
    }

    /**
     * @return array<int, array<int, array>>
     */
    public static function userAccessProvider(): array
    {
        return [
            [200, UserFactory::class],
            [401, AdminFactory::class],
            [401, CompanyFactory::class],
        ];
    }
}
