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
        return [
            'name' => $this->faker->word(),
            'template' => file_get_contents(resource_path('src/assets/img/book-cover.svg')),
            'fields' => [
                [
                    'name' => 'Cover',
                    'key' => 'cover',
                    'type' => 'image',
                ],
                [
                    'name' => 'Author',
                    'key' => 'author',
                    'type' => 'text',
                ],
                [
                    'name' => 'Title Large',
                    'key' => 'titleBig',
                    'type' => 'text',
                ],
                [
                    'name' => 'Title Medium',
                    'key' => 'titleSmall',
                    'type' => 'text',
                ],
            ],
        ];
    }
}
