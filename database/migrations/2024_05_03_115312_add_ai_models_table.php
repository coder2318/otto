<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('value');
        });

        DB::table('settings')->insert(['id' => 1, 'name' => 'ai_service', 'value' => 'Claude3']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
