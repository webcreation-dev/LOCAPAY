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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_last_name');
            $table->string('property_first_name');
            $table->string('property_location');
            $table->string('monthly_rent');
            $table->text('description');
            $table->string('main_image');
            $table->string('owner_phone');
            $table->enum('status', ['Pending', 'Published'])->default('Pending');
            $table->float('rating')->nullable();
            $table->float('general_rating')->nullable();
            $table->float('team_rating')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
