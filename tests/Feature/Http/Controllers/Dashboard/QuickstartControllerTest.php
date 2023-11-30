<?php

namespace Tests\Feature\Http\Controllers\Dashboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Dashboard\QuickstartController
 */
class QuickstartControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $user = $this->createUser();
        \App\Models\QuizQuestion::factory()->times(3)->create();

        $response = $this->actingAs($user)->get(route('dashboard.quickstart.index'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Quickstart')
        );
    }

    /** @test */
    public function store_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Dashboard\QuickstartController::class,
            'store',
            \App\Http\Requests\QuickstartRequest::class
        );
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->post(route('dashboard.quickstart.store'), $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'pronouns' => 'he/him',
            'birth_date' => '01/01/2000',
            'name' => 'John Doe',
            'phone' => '123456789',
            'language' => 'en',
            'country' => 'NL',
            'bio' => 'Lorem ipsum',
            'quiz' => [],
            'social' => null,
        ]);

        $data['birth_date'] = '2000-01-01T00:00:00+00:00';

        $response->assertRedirect();

        /** @var \Spatie\LaravelData\Data */
        $details = $user->details;

        $this->assertEquals($data, $details->toArray());
    }
}
