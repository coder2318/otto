<?php

namespace Tests\Unit\Http\Requests\Chapters;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Chapters\StoreChapterRequest
 */
class StoreChapterRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Chapters\StoreChapterRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Chapters\StoreChapterRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => [
                'required',
                'string',
            ],
            'timeline_id' => [
                'required',
                'integer',
                'exists:timelines,id',
            ],
            'cover' => [
                'sometimes',
                'image',
                'max:10240',
            ],
        ], $actual);
    }
}
