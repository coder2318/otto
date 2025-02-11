<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            PlanSeeder::class,
            TimelineSeeder::class,
            QuizQuestionSeeder::class,
            TimelineQuestionSeeder::class,
            UserSeeder::class,
        ]);

        if (App::isProduction()) {
            return;
        }

        $this->call([
            BookCoverTemplateSeeder::class,
            StorySeeder::class,
            ChapterSeeder::class,
        ]);
    }
}
