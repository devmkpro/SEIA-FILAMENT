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
        Schema::create('bimester_school_diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_diary_id')->constrained()->onDelete('cascade');
            $table->foreignId('period_bimonthlies_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimester_school_diaries');
    }
};
