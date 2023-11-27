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

        $file = $this->faker->unique()->randomElement($files);

        $data = yaml_parse_file($file);

        if ($layout = $data['extends']) {
            unset($data['extends']);
            $data = array_merge_recursive(yaml_parse_file(resource_path("data/book-covers/$layout")), $data);
        }

        return $data;
    }
}
