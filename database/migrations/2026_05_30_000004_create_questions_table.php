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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_section_id')->constrained('test_sections')->cascadeOnDelete();
            $table->foreignId('passage_id')->nullable()->constrained('passages')->nullOnDelete();
            $table->integer('question_number'); // Preserves PDF question numbering (e.g. Q41 etc.)
            $table->text('situation')->nullable(); // Listening situations
            $table->text('question_text');
            $table->string('audio_path')->nullable(); // Listening audios
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
