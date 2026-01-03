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
            $table->uuid('address_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('user_id');
            $table->string('user', 120);
            
            $table->string('cep', 8);
            $table->string('state', 2);
            $table->string('city', 120);
            $table->string('neighborhood', 120);
            $table->string('street', 200);
            $table->string('service', 40)->nullable();
            $table->string('longitude', 200)->nullable();
            $table->string('latitude', 200)->nullable();
            $table->boolean('was_edited')->default(false);
            $table->boolean('is_main_address')->default(false);
            
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
