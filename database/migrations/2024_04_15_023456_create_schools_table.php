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
            $table->string('active')->default(true);
            $table->enum('type', ['Municipal', 'Estadual', 'Federal', 'Privada']);
            $table->enum('category', ['Creche', 'Pré-Escola', 'Fundamental', 'Médio', 'Superior']);
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('zip_code');
            $table->string('phone');
            $table->string('neighborhood')->nullable();
            $table->string('landline')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('complement')->nullable();
            $table->string('acronym')->nullable();
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
