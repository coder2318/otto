<?php

namespace App\Http\Requests\Stories;

use App\Data\Lulu\ShippingAddress;
use Illuminate\Foundation\Http\FormRequest;

class OrderCostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string'],
            'last_name' => ['sometimes', 'required', 'string'],
            'phone' => ['sometimes', 'required', 'string'],
            'email' => ['sometimes', 'required', 'string'],
            'address' => ['sometimes', 'required', 'string'],
            'country_code' => ['sometimes', 'required', 'string'],
            'state_code' => ['sometimes', 'required', 'string'],
            'city' => ['sometimes', 'required', 'string'],
            'postal_code' => ['sometimes', 'required', 'string'],
        ];
    }

    public function shippingAddress(): ?ShippingAddress
    {
        return rescue(fn () => ShippingAddress::from([
            'phone_number' => $this->validated('phone'),
            'city' => $this->validated('city'),
            'country_code' => $this->validated('country_code'),
            'postcode' => $this->validated('postal_code'),
            'state_code' => $this->validated('state_code'),
            'street1' => $this->validated('address'),
        ]), report: false);
    }
}
