<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TranslateController
 */
class TranslateControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function __invoke_validates_with_a_form_request(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TranslateController::class,
            '__invoke',
            \App\Http\Requests\TranslateRequest::class
        );
    }

    /** @test */
    public function invoke_returns_an_ok_response(): void
    {
        $user = $this->createUser(configured: true);

        $response = $this->actingAs($user)->post(route('dashboard.translate'), [
            'text' => 'Hello world!',
            'options' => [
                'format' => 'text',
                'target' => 'uk',
            ],
        ]);

        $response->assertOk();
        $this->assertEquals('Привіт Світ!', $response->json('text'));
    }
}
