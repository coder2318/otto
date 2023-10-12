<?php

namespace Tests\Feature\Http\Controllers;

use Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Mockery;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SocialAuthController
 */
class SocialAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_returns_an_ok_response(): void
    {
        $providers = ['google', 'facebook'];

        foreach ($providers as $provider) {
            $response = $this->get(route('login.socialite', ['provider' => $provider]));
            $response->assertRedirect();
        }
    }

    /** @test */
    public function redirect_returns_an_ok_response(): void
    {
        /** @var Mockery\LegacyMockInterface|User */
        $abstractUser = Mockery::mock(User::class);
        $abstractUser
            ->shouldReceive('getEmail', 'getName', 'getAvatar')
            ->andReturn(fake()->email(), fake()->name(), fake()->imageUrl());

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);
        $response = $this->get(route('login.socialite.redirect', ['provider' => 'google']));

        $response->assertRedirect();
        $this->assertTrue(Auth::check());
    }
}
