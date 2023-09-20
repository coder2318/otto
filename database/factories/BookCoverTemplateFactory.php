<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookCoverTemplate>
 */
class BookCoverTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $files = glob(resource_path('data/book-covers/*.{yaml,yml}'), GLOB_BRACE);

        $file = $this->faker->randomElement($files);

        return yaml_parse_file($file);
    }
}
