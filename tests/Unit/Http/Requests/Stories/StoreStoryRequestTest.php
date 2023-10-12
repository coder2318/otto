<?php

namespace Tests\Unit\Http\Requests\Stories;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Stories\StoreStoryRequest
 */
class StoreStoryRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Stories\StoreStoryRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Stories\StoreStoryRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'story_type_id' => [
                'required',
                'integer',
                'exists:story_types,id',
            ],
        ], $actual);
    }
}
