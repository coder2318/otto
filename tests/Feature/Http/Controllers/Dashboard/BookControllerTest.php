<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\BookController
 */
class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true, subscribed: true);
        $story = \App\Models\Story::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.books.show', ['book' => $story]));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Books/Show')
        );
    }

    // test cases...
}
