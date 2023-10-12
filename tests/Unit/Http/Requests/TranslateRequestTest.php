<?php

namespace Tests\Unit\Http\Requests;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\TranslateRequest
 */
class TranslateRequestTest extends TestCase
{
    /** @var \App\Http\Requests\TranslateRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\TranslateRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'text' => [
                'required',
                'string',
            ],
            'options' => [
                'required',
                'array',
            ],
        ], $actual);
    }
}
