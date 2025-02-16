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
        Schema::create('department_diagnosis_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\DiagnosisGroup::class, 'diagnosis_group_id');
            $table->foreignIdFor(\App\Models\Department::class, 'department_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_diagnosis_groups');
    }
};
