<?php

namespace App\Services;

use App\Data\Lulu\LineItem;
use App\Data\Lulu\ShippingAddress;
use App\Data\Lulu\ShippingOption;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LuluService
{
    protected string $token;

    protected function token(): ?string
    {
        if (isset($this->token)) {
            return $this->token;
        }

        return $this->token = Cache::remember('lulu_token', 3600 - 1, fn () => Http::baseUrl(config('services.lulu.url'))
            ->asForm()
            ->withHeaders([
                'Authorization' => config('services.lulu.encoded_key'),
            ])
            ->post('auth/realms/glasstree/protocol/openid-connect/token', [
                'grant_type' => 'client_credentials',
            ])
            ->json('access_token')
        );
    }

    protected function request(): PendingRequest
    {
        return Http::baseUrl(config('services.lulu.url'))
            ->asJson()
            ->withToken($this->token());
    }

    public function cost(LineItem $item, ShippingAddress $shipping_address, ShippingOption $shipping_option)
    {
        $response = $this->request()->post('print-job-cost-calculations', [
            'line_items' => [$item->toArray()],
            'shipping_address' => $shipping_address->toArray(),
            'shipping_option' => $shipping_option->value,
        ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return $response->json('total_cost_incl_tax');
    }
}
