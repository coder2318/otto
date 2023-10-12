<?php

namespace Tests\Feature\Http\Controllers\Guests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Guests\AuthController
 */
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function invoke_returns_an_ok_response(): void
    {
        $guest = \App\Models\Guest::factory()->create();

        $response = $this->get(URL::signedRoute('login.guests', compact('guest')));

        $response->assertRedirect();

        $this->assertTrue(Auth::guard('web-guest')->id() === $guest->id);
    }

    // test cases...
}
