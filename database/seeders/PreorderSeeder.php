<?php

namespace Database\Seeders;

use App\Models\Preorder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PreorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Preorder::factory(10)->create();
    }
}
