<?php

namespace Tests\Unit\Http\Requests\Stories;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Stories\UpdateStoryRequest
 */
class UpdateStoryRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Stories\UpdateStoryRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Stories\UpdateStoryRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'saveAsNewUserTemplate' => ['sometimes', 'nullable', 'boolean'],
            'cover' => ['sometimes', 'nullable', 'boolean'],
            'meta' => ['sometimes', 'nullable', 'array'],
            'book_cover' => ['sometimes', 'nullable', 'image'],
            'status' => ['sometimes', 'required'],
            'redirect' => ['sometimes', 'nullable'],
        ], $actual);
    }
}
