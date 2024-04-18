<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $fields = [
            [
                'key' => 'descriptionFont',
                'name' => 'Description Text Font',
                'type' => 'font',
                'group' => 'Back',
            ],
            [
                'name' => 'Description Text Background',
                'key' => 'descriptionTextBackground',
                'type' => 'color',
                'group' => 'Back',
            ],
            [
                'name' => 'Description Text Size',
                'key' => 'descriptionTextSize',
                'type' => 'number',
                'group' => 'Back',
            ],
            [
                'key' => 'subtitleFont',
                'name' => 'Subtitle Font',
                'type' => 'font',
                'group' => 'Front',
            ],
            [
                'key' => 'spineTextFont',
                'name' => 'Spine Text Font',
                'type' => 'font',
                'group' => 'Spine',
            ],
            [
                'key' => 'spineBackgroundColor',
                'name' => 'Spine Background Color',
                'type' => 'color',
                'group' => 'Spine',
            ],
            [
                'key' => 'titleFont',
                'name' => 'Title Font',
                'type' => 'font',
                'group' => 'Front',
            ],
            [
                'key' => 'authorFont',
                'name' => 'Author Font',
                'type' => 'font',
                'group' => 'Front',
            ],
            [
                'name' => 'Spine Text Size',
                'key' => 'spineTextSize',
                'type' => 'number',
                'group' => 'Spine',
            ]
        ];

        $templates = DB::table('book_cover_templates')->get();
        foreach ($templates as $template) {
            $decoded = json_decode($template->fields, true);
            $column = array_column($decoded, 'key');
            $flag = false;

            foreach ($fields as $newField) {
                if (!in_array($newField['key'], $column)) {
                    $decoded[] = $newField;
                    $flag = true;
                }
            }

            if ($flag) {
                DB::table('book_cover_templates')
                    ->where(['id' => $template->id])
                    ->update(['fields' => $decoded]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
