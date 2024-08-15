<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::update("UPDATE `stories` SET `font` = '" . config("story.default_font") . "' WHERE `font` = 'Baskerville'");
        Schema::table('stories', function (Blueprint $table) {
            $table->string('font')->default(config('story.default_font'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::update("UPDATE `stories` SET `font` = 'Baskerville' WHERE `font` = '" . config("story.default_font") . "'");
    }
};
