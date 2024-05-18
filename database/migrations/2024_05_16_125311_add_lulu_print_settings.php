<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\LuluPrintSettings;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lulu_print_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_enabled');

            $luluSettings = LuluPrintSettings::getAvailableOptions();
            foreach ($luluSettings as $fieldName => $valuesByKeys) {
                $possibleKeys = array_keys($valuesByKeys);
                $table->enum($fieldName, $possibleKeys);
            }
        });

        DB::table('lulu_print_settings')->insert([
            'name' => 'default',
            'is_enabled' => true,
            'book_type' => 'Royal',
            'color_type' => 'Color',
            'print_type' => 'Standard',
            'bind_type' => 'Case Wrap',
            'paper_type' => '80# Coated White',
            'finish_type' => 'Matte',
            'linen_type' => 'N/A',
            'foil_type' => 'N/A'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lulu_print_settings');
    }
};
