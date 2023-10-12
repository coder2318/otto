<?php

namespace Tests\Unit\Http\Requests;

use App\Data\User\Details;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\QuickstartRequest
 */
class QuickstartRequestTest extends TestCase
{
    /** @var \App\Http\Requests\QuickstartRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\QuickstartRequest();
    }

    /** @test */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertEquals(Details::getValidationRules([]), $actual);
    }
}
