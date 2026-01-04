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
        Schema::create('address_data', function (Blueprint $table) {
            $table->id();            
            $table->string('cep', 8);
            $table->string('state', 2);
            $table->string('city', 120);
            $table->string('neighborhood', 120);
            $table->string('street', 200);
            $table->string('service', 40)->nullable();
            $table->string('longitude', 200)->nullable();
            $table->string('latitude', 200)->nullable();
            $table->boolean('was_edited')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_models');
    }
};
