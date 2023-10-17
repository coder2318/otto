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
        Chapter::factory(5)->withStory()->withTimeline()->create();
        Chapter::factory(3)->withStory()->withTimeline()->withTimelineQuestion()->create();
    }
}
