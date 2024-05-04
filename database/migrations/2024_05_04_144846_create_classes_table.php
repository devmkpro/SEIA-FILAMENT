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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('active', ['Ativa', 'Inativa'])->default('Ativa');
            $table->foreignId('school_id')->constrained();
            $table->foreignId('period_school_years_id')->constrained();
            $table->foreignId('curriculum_id')->constrained();
            $table->string('name');
            $table->enum('modality', ['Regular', 'EJA', 'Tecnico', 'Especializacao', 'Mestrado', 'Doutorado']);
            $table->enum('turn', ['Manhã', 'Tarde', 'Noite', 'Integral']);
            $table->integer('max_students');

            $table->unsignedBigInteger('teacher_responsible_id')->nullable();
            $table->foreign('teacher_responsible_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
