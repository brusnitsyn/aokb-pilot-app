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
        Schema::create('department_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\DepartmentQuestion::class, 'department_question_id'); // Связь с вопросом
            $table->string('text'); // Текст ответа
            $table->double('score'); // Баллы за ответ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_answers');
    }
};
