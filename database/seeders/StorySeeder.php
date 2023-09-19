<?php

namespace Database\Seeders;

use App\Models\Story;
use App\Models\StoryType;
use App\Models\User;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Story::factory(1)->create([
            'user_id' => User::value('id'),
            'story_type_id' => StoryType::value('id'),
        ])->each(function (Story $story) {
            $story->addMediaFromUrl('https://picsum.photos/640/480')
                ->withResponsiveImages()
                ->toMediaCollection('cover');
        });
    }
}
