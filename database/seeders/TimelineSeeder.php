<?php

namespace Database\Seeders;

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

        foreach ($data as $datum) {
            Timeline::create($datum);
        }
    }
}
