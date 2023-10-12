<?php

namespace Tests\Unit\Services;

use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\LazyCollection;
use PHPUnit\Framework\TestCase;

class SegmentationTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    public function test_text_segmentation(): void
    {
        $callable = [OpenAIService::class, 'segmentate'];
        $words = 10;
        $maxWords = 700;
        $sentence = '';

        for ($i = 0; $i < 100; $i++) {
            $sentence .= $this->faker()->sentence($words).' ';
        }

        $collection = LazyCollection::make(fn () => call_user_func($callable, $sentence, $maxWords));

        $this->assertEquals(ceil($words * 100 / $maxWords), $collection->count());
    }
}
