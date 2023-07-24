<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = config('services.stripe.plans');

        foreach ($plans as $plan) {
            Plan::factory()->create([
                'stripe_plan' => $plan,
            ]);
        }
    }
}
