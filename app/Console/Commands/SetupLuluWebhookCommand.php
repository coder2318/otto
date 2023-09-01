<?php

namespace App\Console\Commands;

use App\Services\LuluService;
use Illuminate\Console\Command;

class SetupLuluWebhookCommand extends Command
{
    protected $signature = 'app:lulu-webhook';

    protected $description = 'Setup Lulu Webhook';

    public function handle(LuluService $lulu): int
    {
        $active = $lulu->webhook(route('webhook.lulu'), ['PRINT_JOB_STATUS_CHANGED']);

        if (! $active) {
            $this->error('Unable to setup Lulu webhook');

            return Command::FAILURE;
        }

        $this->info('Webhook setup successfully!');

        return Command::SUCCESS;
    }
}
