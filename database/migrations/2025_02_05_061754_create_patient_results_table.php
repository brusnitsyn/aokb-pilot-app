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
        Schema::create('patient_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Patient::class, 'patient_id'); // Связь с пациентом
            $table->foreignIdFor(\App\Models\Department::class, 'from_department_id'); // Связь с медицинской организацией (откуда)
            $table->foreignIdFor(\App\Models\Department::class, 'to_department_id'); // Связь с медицинской организацией (куда)
            $table->double('patient_score'); // Баллы пациента
            $table->double('department_score'); // Баллы медицинской организации
            $table->double('total_score'); // Общий результат
            $table->foreignIdFor(\App\Models\Scenario::class, 'scenario_id')->nullable(); // Связь со сценарием
            $table->double('scenario_score')->default(0); // Баллы сценария
            $table->json('patient_responses')->nullable(); // Выбранные ответы на вопросы пациента
            $table->json('department_responses')->nullable(); // Выбранные ответы на вопросы МО
            $table->foreignIdFor(\App\Models\PatientResultStatus::class, 'status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_results');
    }
};
