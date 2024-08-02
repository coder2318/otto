<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_user_cover_templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('parameters')->nullable();
            $table->unsignedInteger('template_id')->nullable();
            $table->unsignedInteger('story_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_user_cover_templates');
    }
};
