<?php

namespace Tests\Feature\Http\Controllers\Guests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Guests\SetupController
 */
class SetupControllerTest extends TestCase
{
    /** @test */
    public function post_setup_returns_an_ok_response(): void
    {
        $user = $this->createGuestUser();

        $response = $this->actingAs($user, 'web-guest')->post(route('guests.setup.post'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            'relationship' => $rel = fake()->words(2, true),
        ]);

        $response->assertRedirect();
        $this->assertTrue(Auth::guard('web-guest')->user()->details['relationship'] === $rel);
    }

    /** @test */
    public function setup_returns_an_ok_response(): void
    {
        $user = $this->createGuestUser();

        $response = $this->actingAs($user, 'web-guest')->get(route('guests.setup'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Guests/Setup')
        );
    }
}
