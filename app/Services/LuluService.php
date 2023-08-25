<?php

namespace App\Services;

use App\Data\Lulu\LineItem;
use App\Data\Lulu\ShippingAddress;
use App\Data\Lulu\ShippingOption;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class LuluService
{
    protected string $token;

    protected function token(): ?string
    {
        if (isset($this->token)) {
            return $this->token;
        }

        return $this->token = Http::baseUrl(config('services.lulu.url'))
            ->withHeaders([
                'Authorization' => config('services.lulu.encoded_key'),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->post('auth/realms/glasstree/protocol/openid-connect/token', [
                'grant_type' => 'client_credentials',
            ])
            ->json('access_token');
    }

    protected function request(): PendingRequest
    {
        return Http::baseUrl(config('services.lulu.url'))
            ->asJson()
            ->withToken($this->token());
    }

    public function cost(LineItem $item, ShippingAddress $shipping_address, ShippingOption $shipping_option)
    {
        return $this->request()->post('print-job-cost-calculations', [
            'line_items' => [$item->toArray()],
            'shipping_address' => $shipping_address->toArray(),
            'shipping_option' => $shipping_option->value,
        ])->json('total_cost_incl_tax');
    }
}
