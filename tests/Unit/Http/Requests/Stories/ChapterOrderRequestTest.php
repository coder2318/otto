<?php

namespace Tests\Unit\Http\Requests\Stories;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Stories\ChapterOrderRequest
 */
class ChapterOrderRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Stories\ChapterOrderRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Stories\ChapterOrderRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'timelines' => [
                'nullable',
                'array',
            ],
            'timelines.*' => [
                'required',
                'array',
            ],
            'timelines.*.id' => [
                'required',
                'integer',
                'exists:timelines,id',
            ],
            'timelines.*.chapters' => [
                'nullable',
                'array',
                'exists:chapters,id',
            ],
            'timelines.*.chapters.*' => [
                'required',
                'integer',
            ],
            'redirect' => [
                'sometimes',
                'nullable',
            ],
        ], $actual);
    }
}
