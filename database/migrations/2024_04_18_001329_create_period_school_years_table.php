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
        Schema::create('period_school_years', function (Blueprint $table) {
            $table->id();
            $table->enum('active', ['Ativa', 'Inativa'])->default('Ativa');
            $table->foreignId('school_year_id')->constrained();
            $table->foreignId('school_id')->constrained();
            $table->enum("type", ["Bimestral", "Semestral"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_school_years');
    }
};
