<?php

namespace Tests\Feature\Services;

use App\Data\Lulu\LineItem;
use App\Data\Lulu\ShippingAddress;
use App\Data\Lulu\ShippingOption;
use App\Services\LuluService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LuluTest extends TestCase
{
    /** @test */
    public function cost()
    {
        Http::fake([
            config('services.lulu.url').'/auth/realms/glasstree/protocol/openid-connect/token' => Http::response([
                'access_token' => 'token',
            ]),
            config('services.lulu.url').'/print-job-cost-calculations' => Http::response([
                'currency' => 'USD',
                'line_item_costs' => [
                    [
                        'cost_excl_discounts' => '1.70',
                        'cost_excl_tax' => '1.70',
                        'discounts' => [],
                        'quantity' => 20,
                        'tax_rate' => '0.000000',
                        'total_cost_excl_discounts' => '34.00',
                        'total_cost_excl_tax' => '34.00',
                        'total_cost_incl_tax' => '34.00',
                        'total_tax' => '0.00',
                    ],
                ],
                'shipping_cost' => [
                    'tax_rate' => '0.000000',
                    'total_cost_excl_tax' => '132.74',
                    'total_cost_incl_tax' => '132.74',
                    'total_tax' => '0.00',
                ],
                'total_cost_excl_tax' => '3224.74',
                'total_cost_incl_tax' => '3224.74',
                'total_discount_amount' => '0.00',
                'total_tax' => '0.0',
            ]),
        ]);

        /** @var LuluService */
        $service = $this->app->make(LuluService::class);

        $cost = $service->cost(
            LineItem::from([
                'page_count' => 32,
                'pod_package_id' => '0600X0900BWSTDPB060UW444MXX',
                'quantity' => 20,
            ]),
            ShippingAddress::from([
                'phone_number' => '+1-212-456-7890',
                'city' => 'Washington',
                'country_code' => 'US',
                'postcode' => '20540',
                'state_code' => 'DC',
                'street1' => '101 Independence Ave SE',
            ]),
            ShippingOption::EXPRESS
        );

        $this->assertEquals(3224.74, $cost);

        Http::assertSent(fn ($request) => $request->method() === 'POST' &&
            $request->url() === config('services.lulu.url').'/auth/realms/glasstree/protocol/openid-connect/token'
        );

        Http::assertSent(fn ($request) => $request->method() === 'POST' &&
            $request->url() === config('services.lulu.url').'/print-job-cost-calculations'
        );
    }

    /** @test */
    public function print()
    {
        Http::fake([
            config('services.lulu.url').'/print-jobs' => Http::response([
                'id' => '1234',
            ]),
        ], 201);

        /** @var LuluService */
        $service = $this->app->make(LuluService::class);

        $job = $service->print(
            'a@b.com',
            LineItem::from([
                'page_count' => 32,
                'pod_package_id' => '0600X0900BWSTDPB060UW444MXX',
                'quantity' => 20,
            ]),
            ShippingAddress::from([
                'phone_number' => '+1-212-456-7890',
                'city' => 'Washington',
                'country_code' => 'US',
                'postcode' => '20540',
                'state_code' => 'DC',
                'street1' => '101 Independence Ave SE',
            ]),
            ShippingOption::EXPRESS
        );

        $this->assertEquals('1234', $job['id']);
    }
}
