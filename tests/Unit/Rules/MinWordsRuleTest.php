<?php

namespace Tests\Unit\Rules;

use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class MinWordsRuleTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    /** @test */
    public function it_returns_the_correct_message()
    {
        $rule = new \App\Rules\MinWordsRule(5);

        $correct = $this->faker->words(6, true);
        $wrong = $this->faker->words(4, true);

        $rule->validate('correct', $correct, fn () => $this->fail());
        $rule->validate('wrong', $wrong, fn (string $fail) => $this->assertEquals('validation.min.words', $fail));
    }
}
