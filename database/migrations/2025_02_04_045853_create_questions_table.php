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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->foreignIdFor( \App\Models\Answer::class, 'depends_on_answer_id')
                ->nullable();
            $table->string('type')->default('single');
            $table->boolean('requires_confirmation')->default(false); // Требуется ли подтверждение
            $table->boolean('requires')->default(true); // Обязательна ли проверка на наличие значения
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
