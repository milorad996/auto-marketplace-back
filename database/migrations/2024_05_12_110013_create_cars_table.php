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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('model_id');
            $table->decimal('price', 10, 2);
            $table->integer('manufacture_year');
            $table->integer('mileage');
            $table->string('body_type');
            $table->string('fuel_type');
            $table->integer('door_count');
            $table->timestamps();

            $table->index('price');
            $table->index('manufacture_year');
            $table->index('mileage');
            $table->index('body_type');
            $table->index('fuel_type');
            $table->index('door_count');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('car_models')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
