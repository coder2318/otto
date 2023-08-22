<?php

use App\Models\Story;
use App\Models\Timeline;
use App\Models\TimelineQuestion;
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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->text('title');
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->string('status');

            $table->foreignIdFor(Story::class)
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Timeline::class)->nullable()
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(TimelineQuestion::class)->nullable()
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
