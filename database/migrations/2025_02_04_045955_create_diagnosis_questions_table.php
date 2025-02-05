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
        Schema::create('diagnosis_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Diagnosis::class, 'diagnosis_id');
            $table->foreignIdFor(\App\Models\Question::class, 'question_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_questions');
    }
};
