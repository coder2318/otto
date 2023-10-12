<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('createSetupIntent')->andReturn('null');
        });

        $response = $this->actingAs($user)->get(route('dashboard.plans.index'));

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

        $response = $this->actingAs($user)->get(route('dashboard.plans.show', [$plan]));

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

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        $plan = \App\Models\Plan::factory()->create();
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->put(route('dashboard.plans.update', [$plan]), [
            'payment_method' => 'card_123',
            'price_id' => 'price_123',
        ]);

        $response->assertRedirect();

        $response->assertSessionHasNoErrors();
    }
}
