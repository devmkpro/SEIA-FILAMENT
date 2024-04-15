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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('active', ['Ativa', 'Inativa']);
            $table->enum('type', ['Municipal', 'Estadual', 'Federal', 'Privada']);
            $table->enum('category', ['Creche', 'Pré-Escola', 'Fundamental', 'Médio', 'Superior']);
            $table->string('name', 200);
            $table->string('email', 200);
            $table->string('address', 200);
            $table->string('zip_code', 20);
            $table->string('phone', 20);
            $table->string('neighborhood')->nullable();
            $table->string('landline')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('complement')->nullable();
            $table->string('acronym', 10)->nullable();
            $table->foreignId('city_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
