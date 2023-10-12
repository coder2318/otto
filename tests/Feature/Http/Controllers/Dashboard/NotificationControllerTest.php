<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\NotificationController
 */
class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function read_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->post(route('dashboard.notifications.read'));

        $response->assertOk();
    }
}
