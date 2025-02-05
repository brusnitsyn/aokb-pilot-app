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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(); // Имя пациента
            $table->string('last_name')->nullable(); // Фамилия пациента
            $table->string('middle_name')->nullable(); // Отчество пациента
            $table->string('full_name')->nullable(); // ФИО пациента
            $table->double('total_score'); // Общий балл
            $table->foreignIdFor(\App\Models\Diagnosis::class, 'diagnosis_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
