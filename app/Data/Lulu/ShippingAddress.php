<?php

namespace App\Data\Lulu;

use Spatie\LaravelData\Data;

class ShippingAddress extends Data
{
    public string $city;

    public string $country_code;

    public ?string $email;

    public ?bool $is_business;

    public ?string $name;

    public ?string $organization;

    public string $phone_number;

    public string $postcode;

    public ?string $state_code;

    public string $street1;

    public ?string $street2;

    public ?string $title;

    public function toArray(): array
    {
        return collect(parent::toArray())->filter()->all();
    }
}
