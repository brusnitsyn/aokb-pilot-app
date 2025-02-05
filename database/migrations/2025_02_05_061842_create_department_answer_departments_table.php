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
        Schema::create('department_answer_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor( \App\Models\DepartmentAnswer::class, 'department_answer_id'); // Связь с ответом
            $table->foreignIdFor(\App\Models\Department::class, 'department_id'); // Связь с медицинской организацией
            $table->double('score'); // Баллы для конкретной медицинской организации
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_answer_departments');
    }
};
