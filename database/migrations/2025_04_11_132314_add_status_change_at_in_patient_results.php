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
        Schema::table('patient_results', function (Blueprint $table) {
            $table->unsignedBigInteger('last_status_at')->nullable();
            $table->unsignedBigInteger('status_changed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_results', function (Blueprint $table) {
            $table->dropColumn('last_status_at');
            $table->dropColumn('status_changed_at');
        });
    }
};
