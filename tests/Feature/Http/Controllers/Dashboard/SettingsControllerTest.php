<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\SettingsController
 */
class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function billing_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);
        \App\Models\Plan::factory()->times(3)->create();

        $response = $this->actingAs($user)->get(route('dashboard.settings.billing'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Settings/Billing')
        );
    }

    /** @test */
    public function notifications_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.settings.notifications'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Settings/Notifications')
        );
    }

    /** @test */
    public function password_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.settings.password'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Settings/Password')
        );
    }

    /** @test */
    public function update_password_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->put(route('dashboard.settings.password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }
}
