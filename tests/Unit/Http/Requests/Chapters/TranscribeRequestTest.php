<?php

namespace Tests\Unit\Http\Requests\Chapters;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Chapters\TranscribeRequest
 */
class TranscribeRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Chapters\TranscribeRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Chapters\TranscribeRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'attachments' => [
                'required',
                'array',
            ],
            'attachments.*' => [
                'required',
                'integer',
                'exists:media,id',
            ],
        ], $actual);
    }
}
