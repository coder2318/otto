<?php

namespace Tests\Feature\Http\Controllers;

use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StaticController
 */
class StaticControllerTest extends TestCase
{
    /** @test */
    public function about_returns_an_ok_response(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('About')
        );
    }

    /** @test */
    public function contact_returns_an_ok_response(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Contact')
        );
    }

    /** @test */
    public function faq_returns_an_ok_response(): void
    {
        $response = $this->get(route('faq'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('FAQ')
        );
    }

    /** @test */
    public function index_returns_an_ok_response(): void
    {
        $response = $this->get(route('index'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Index')
        );
    }

    /** @test */
    public function post_contact_returns_an_ok_response(): void
    {
        $response = $this->post(route('contact.store'), $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'message' => fake()->text(),
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function post_preorder_returns_an_ok_response(): void
    {
        $response = $this->post(route('preorder.store'), [
            'name' => fake()->name(),
            'email' => fake()->email(),
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function privacy_policy_returns_an_ok_response(): void
    {
        $response = $this->get(route('privacy-policy'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PrivacyPolicy')
        );
    }

    /** @test */
    public function terms_and_conditions_returns_an_ok_response(): void
    {
        $response = $this->get(route('terms-and-conditions'));

        $response->assertOk();

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('TermsAndConditions')
        );
    }
}
