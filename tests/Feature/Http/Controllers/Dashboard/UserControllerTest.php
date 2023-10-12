<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.users.edit'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Users/Edit')
        );
    }

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->get(route('dashboard.users.show', [$user]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Users/Show')
        );
    }

    /** @test */
    public function update_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\UserController::class,
            'update',
            \App\Http\Requests\Users\UpdateUserRequest::class
        );
    }

    /** @test */
    public function update_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->put(route('dashboard.users.update'), [
            'name' => 'CoolUsername',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'CoolUsername',
        ]);
    }
}
