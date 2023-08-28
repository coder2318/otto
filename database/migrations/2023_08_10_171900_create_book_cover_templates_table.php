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
        Schema::create('book_cover_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('fields');
            $table->text('back');
            $table->text('spine');
            $table->text('front');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_cover_templates');
    }
};
