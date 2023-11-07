<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\PlanController
 */
class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        \App\Models\Plan::factory()->times(3)->create();
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('plans.index'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Plans/Index')
        );
    }

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        $plan = \App\Models\Plan::factory()->create();
        $user = $this->createUser(configured: true);

        $this->mock(Request::class, fn ($mock) => $mock->shouldReceive('user->createSetupIntent')->andReturn());

        $response = $this->actingAs($user)->get(route('plans.show', [$plan]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Plans/Show')
        );
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\PlanController::class,
            'update',
            \App\Http\Requests\SubscriptionRequest::class
        );
    }
}
