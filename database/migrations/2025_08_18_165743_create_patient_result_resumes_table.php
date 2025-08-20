<?php

use App\Models\PatientResult;
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
        Schema::create('patient_result_resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PatientResult::class, 'patient_result_id')->constrained();
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_result_resumes');
    }
};
