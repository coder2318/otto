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
            'back' => '',
            'spine' => <<<'XML'
            <rect x="0" y="0" width="100%" height="100%" fill="white" />
            <text
                x="50%"
                y="50%"
                width="100%"
                text-anchor="middle"
                dominant-baseline="middle"
                height="100%"
                style="transform-origin:center;transform:rotate(-90deg);"
                font-size="calc(var(--spine-width) * 0.666)"
                data-spine="innerText"
            ></text>
            XML,
            'front' => <<<'XML'
            <text x="50%" y="50" font-size="2rem" text-anchor="middle" data-title-medium="innerText">Title Medium</text>
            <text x="50%" y="150" font-size="5rem" text-anchor="middle" data-title-large="innerText">Title Large</text>
            <g transform="translate(0 -50)">
                <text x="50%" y="100%" font-size="2rem" text-anchor="middle" data-author="innerText">Author</text>
            </g>
            XML,
            'fields' => [
                [
                    'name' => 'Cover Background',
                    'key' => 'background',
                    'type' => 'image',
                ],
                [
                    'name' => 'Author',
                    'key' => 'author',
                    'type' => 'text',
                ],
                [
                    'name' => 'Title Large',
                    'key' => 'titleLarge',
                    'type' => 'text',
                ],
                [
                    'name' => 'Title Medium',
                    'key' => 'titleMedium',
                    'type' => 'text',
                ],
                [
                    'name' => 'Spine',
                    'key' => 'spine',
                    'type' => 'text',
                ],
            ],
        ];
    }
}
