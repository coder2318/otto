<?php

namespace Database\Seeders;

use App\Models\StoryType;
use App\Models\Timeline;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Childhood',
                'description' => 'Childhood',
            ],
            [
                'title' => 'Teens',
                'description' => 'Teens',
            ],
            [
                'title' => 'Young Adulthood',
                'description' => 'Young Adulthood',
            ],
            [
                'title' => 'Adulthood',
                'description' => 'Adulthood',
            ],
            [
                'title' => 'Senior Adulthood',
                'description' => 'Senior Adulthood',
            ],
        ];

        /** @var StoryType */
        $storyType = StoryType::create([
            'name' => 'Autobiography',
        ]);

        foreach ($data as $datum) {
            $storyType->timelines()->create($datum);
        }
    }
}
