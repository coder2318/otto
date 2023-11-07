<?php

namespace Tests\Unit\Http\Requests\Chapters;

use Tests\TestCase;

/**
 * @see \App\Http\Requests\Chapters\UploadFilesRequest
 */
class UploadFilesRequestTest extends TestCase
{
    /** @var \App\Http\Requests\Chapters\UploadFilesRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\Chapters\UploadFilesRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules([
            'files' => [
                'required',
                'array',
            ],
            'files.*' => [
                'required',
                'file',
                'mimetypes:audio/*,text/plain,document/*',
                'max:51200',
            ],
        ], $actual);
    }
}
