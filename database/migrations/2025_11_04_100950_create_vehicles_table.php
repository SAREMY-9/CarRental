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
        Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade');
        $table->foreignId('location_id')->constrained()->onDelete('cascade');
        $table->string('make');
        $table->string('model');
        $table->string('registration_number')->unique();
        $table->year('year');
        $table->decimal('price_per_day', 10, 2);
        $table->enum('status', ['available', 'unavailable'])->default('available');
        $table->string('image')->nullable(); 
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
