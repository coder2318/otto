<?php

namespace Tests\Unit\Http\Requests\Chapters;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Chapters\UpdateChapterRequest
 */
class UpdateChapterRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Chapters\UpdateChapterRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Chapters\UpdateChapterRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'title' => [
                'sometimes',
                'required',
                'string',
            ],
            'content' => [
                'sometimes',
                'required',
                'string',
            ],
            'edit' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'timeline_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:timelines,id',
            ],
            'cover' => [
                'sometimes',
                'required',
                'image',
                'max:10240',
            ],
            'attachments' => [
                'sometimes',
                'nullable',
                'array',
            ],
            'attachments.*.file' => [
                'required',
                'file',
                'mimes:webm,weba,wav,mp3,text,txt,pdf,docx,m4a,m2a',
                'max:20480',
            ],
            'attachments.*.options' => [
                'nullable',
                'array',
            ],
            'attachments.*.translate' => [
                'sometimes',
                'array',
            ],
            'attachments.*.translate.source' => [
                'nullable',
                'string',
            ],
            'attachments.*.translate.target' => [
                'nullable',
                'string',
            ],
            'status' => [
                'sometimes',
                'required',
            ],
            'redirect' => [
                'sometimes',
                'nullable',
            ],
        ], $actual);
    }
}
