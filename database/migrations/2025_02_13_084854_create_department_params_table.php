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
        Schema::create('department_params', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Department::class, 'department_id');
            $table->foreignIdFor(\App\Models\Param::class, 'param_id');
            $table->foreignIdFor(\App\Models\ParamValue::class, 'param_value_id');
            $table->json('depends_diagnosis_group_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_params');
    }
};
