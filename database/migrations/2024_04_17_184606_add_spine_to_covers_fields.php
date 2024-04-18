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
                'key' => 'spineBackgroundColor',
                'name' => 'Spine Background Color',
                'type' => 'color',
                'group' => 'Spine'
            ],
            [
                'key' => 'titleFont',
                'name' => 'Title Font',
                'type' => 'font',
                'group' => 'Front'
            ],
        ];
        $templates = DB::table('book_cover_templates')->get();
        foreach ($templates as $template) {
            $decoded = json_decode($template->fields, true);
            $column = array_column($decoded, 'key');
            $flag = false;
            if(!in_array('spineBackgroundColor', $column)) {
                $decoded[] = $fields[0];
                $flag = true;
            }
            if(!in_array('titleFont', $column)) {
                $decoded[] = $fields[1];
                $flag = true;
            }
            if($flag) {
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
