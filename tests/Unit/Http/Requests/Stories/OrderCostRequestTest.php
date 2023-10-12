<?php

namespace Tests\Unit\Http\Requests\Stories;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Stories\OrderCostRequest
 */
class OrderCostRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Stories\OrderCostRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Stories\OrderCostRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'first_name' => [
                'sometimes',
                'required',
                'string',
            ],
            'last_name' => [
                'sometimes',
                'required',
                'string',
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
            ],
            'address' => [
                'sometimes',
                'required',
                'string',
            ],
            'country_code' => [
                'sometimes',
                'required',
                'string',
            ],
            'state_code' => [
                'sometimes',
                'required',
                'string',
            ],
            'city' => [
                'sometimes',
                'required',
                'string',
            ],
            'postal_code' => [
                'sometimes',
                'required',
                'string',
            ],
        ], $actual);
    }
}
