<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\WebhookController
 */
class WebhookControllerTest extends TestCase
{
    /** @test */
    public function lulu_returns_an_ok_response(): void
    {
        $response = $this->postJson(route('webhook.lulu'), [
            'foo' => 'bar',
        ]);

        $response->assertOk();
    }
}
