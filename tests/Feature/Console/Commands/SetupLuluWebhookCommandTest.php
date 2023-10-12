<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\SetupLuluWebhookCommand;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\SetupLuluWebhookCommand
 */
class SetupLuluWebhookCommandTest extends TestCase
{
    /** @test */
    public function it_runs_successfully()
    {
        Http::fake([
            config('services.lulu.url').'/auth/realms/glasstree/protocol/openid-connect/token' => Http::response([
                'access_token' => 'token',
            ]),
            config('services.lulu.url').'/webhooks' => Http::response([
                'is_active' => true,
            ]),
        ]);

        $this->artisan(SetupLuluWebhookCommand::class)
            ->assertExitCode(0)
            ->run();

        // TODO: perform additional assertions to ensure the command behaved as expected
    }
}
