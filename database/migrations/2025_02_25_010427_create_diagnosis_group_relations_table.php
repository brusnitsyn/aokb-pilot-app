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
        Schema::create('diagnosis_group_relations', function (Blueprint $table) {
            $table->id();
            $table->morphs('diagnosable');
            $table->foreignIdFor(\App\Models\DiagnosisGroup::class, 'diagnosis_group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_group_relations');
    }
};
