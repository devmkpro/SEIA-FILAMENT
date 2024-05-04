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
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->string('series')
                ->enum(
                    'educacao_infantil',
                    'fundamental_i',
                    'fundamental_ii',
                    'ensino_medio',
                    'eja',
                    'tecnico',
                    'other',
                );
            $table->double('weekly_hours');
            $table->double('total_hours');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('modality')
                ->enum(
                    'bercario',
                    'creche',
                    'pre_escola',
                    'fundamental',
                    'medio',
                    'eja',
                    'educacao_especial',
                    'tecnico',
                    'other'
                );
            $table->double('default_time_class');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
