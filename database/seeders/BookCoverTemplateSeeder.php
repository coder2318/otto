<?php

namespace Database\Seeders;

use App\Models\BookCoverTemplate;
use Illuminate\Database\Seeder;

class BookCoverTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookCoverTemplate::factory()->create();
    }
}
