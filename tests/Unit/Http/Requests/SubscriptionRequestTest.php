<?php

namespace Tests\Unit\Http\Requests;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\SubscriptionRequest
 */
class SubscriptionRequestTest extends TestCase
{
    /** @var \App\Http\Requests\SubscriptionRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\SubscriptionRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'payment_method' => [
                'required',
                'string',
            ],
            'price_id' => [
                'required',
                'string',
            ],
        ], $actual);
    }
}
