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
        Schema::create('param_values', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Param::class, 'param_id');
            $table->string('value_name'); // Наименование значения параметра
            $table->double('score')->nullable(); // Баллы за значение параметра
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('param_values');
    }
};
