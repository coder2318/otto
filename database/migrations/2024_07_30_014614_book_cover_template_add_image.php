<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_cover_templates', function (Blueprint $table) {
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('book_cover_templates', function (Blueprint $table) {
            $table->dropColumn('front_image');
            $table->dropColumn('back_image');
        });
    }
};
