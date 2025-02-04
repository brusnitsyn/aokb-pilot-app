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
        Schema::create('department_questions', function (Blueprint $table) {
            $table->id();
            // $table->foreignIdFor(\App\Models\Department::class, 'department_id'); // Связь с медицинской организацией
            $table->string('text'); // Текст вопроса
            $table->foreignIdFor(\App\Models\DepartmentAnswer::class, 'depends_on_answer_id')
                ->nullable(); // Зависимость от ответа
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_questions');
    }
};
