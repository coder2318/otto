<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $templates = DB::table('book_cover_templates')->get();
        foreach ($templates as $template) {
            $this->updateFrontContent($template, 'data-title-color="fill"', "\n  data-title-font=\"font-family\"");
        }
    }

    private function updateFrontContent($template, $searchString, $newText): void
    {
        $frontContent = $template->front;
        $position = strpos($frontContent, $searchString);

        $newTextPosition = strpos($frontContent, $newText);

        if ($position !== false && $newTextPosition === false) {
            $position += strlen($searchString);

            $updatedFrontContent = substr_replace($frontContent, $newText, $position, 0);

            DB::table('book_cover_templates')
                ->where('id', $template->id)
                ->update(['front' => $updatedFrontContent]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
