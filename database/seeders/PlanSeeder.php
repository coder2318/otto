<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Stripe\Price;
use Stripe\StripeClient;

class PlanSeeder extends Seeder
{
    public function __construct(
        protected StripeClient $stripe
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = config('services.stripe.plans');

        foreach ($plans as $plan) {
            Plan::create($this->getProduct($plan));
        }
    }

    protected function getProduct(string $productId)
    {
        $product = $this->stripe->products->retrieve($productId);
        $prices = $this->stripe->prices->all(['product' => $productId]);

        return [
            'name' => $product->name,
            'slug' => Str::slug($product->name),
            'description' => $product?->description,
            'prices' => collect($prices)->mapWithKeys(fn (Price $price) => [
                $price->id => [
                    'interval' => $price?->recurring?->interval ?? 'unlimited',
                    'interval_count' => $price?->recurring?->interval_count,
                    'value' => $price?->unit_amount / 100,
                    'currency' => $price?->currency,
                ],
            ])->sortBy('value')->all(),
            'features' => $product?->features ? array_map(fn ($feature) => $feature['name'], $product?->features) : [],
            'metadata' => $product?->metadata ?? [],
        ];
    }
}
