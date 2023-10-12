<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SubscriptionFactory extends Factory
{
    protected $model = \Laravel\Cashier\Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'default',
            'stripe_plan' => $this->faker->randomElement(['plan-1', 'plan-2', 'plan-3']),
            'quantity' => 1,
        ];
    }
}
