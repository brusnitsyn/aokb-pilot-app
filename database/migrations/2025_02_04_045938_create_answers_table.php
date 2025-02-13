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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Question::class, 'question_id'); // Связь с вопросом
            $table->string('text'); // Текст ответа
            $table->double('score'); // Баллы за ответ
            $table->foreignIdFor(\App\Models\Scenario::class, 'scenario_id')->nullable(); // Сценарий
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
