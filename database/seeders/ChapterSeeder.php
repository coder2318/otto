<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chapter::factory(100)->withStory()->withTimeline()->create()->each(function (Chapter $chapter) {
            $chapter->addMediaFromUrl('https://picsum.photos/640/480')
                ->withResponsiveImages()
                ->toMediaCollection('cover');
        });
    }
}
