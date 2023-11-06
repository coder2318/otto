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
        Schema::table('book_cover_templates', function (Blueprint $table) {
            $table->text('base')->nullable()->after('fields');
            $table->text('back')->nullable()->change();
            $table->text('spine')->nullable()->change();
            $table->text('front')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_cover_templates', function (Blueprint $table) {
            $table->dropColumn('base');
            $table->text('back')->nullable(false)->change();
            $table->text('spine')->nullable(false)->change();
            $table->text('front')->nullable(false)->change();
        });
    }
};
