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
        Schema::create('period_semesters', function (Blueprint $table) {
            $table->id();
            $table->enum('active', ['Ativa', 'Inativa'])->default('Ativa');
            $table->foreignId('period_school_years_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('semester', ['1º Semestre','2º Semestre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_semesters');
    }
};
