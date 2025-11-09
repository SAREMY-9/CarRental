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
        // Prevent duplicate table creation
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();

                // Foreign Keys (from Model relationships)
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
                
                // Location IDs (using nullable for dropoff as per Body data)
                $table->foreignId('pickup_location_id')->constrained('locations')->onDelete('cascade');
                $table->foreignId('dropoff_location_id')->nullable()->constrained('locations')->onDelete('set null');
                
                // Booking Dates and Status
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->string('status')->default('pending'); // The controller checks for 'cancelled'
                
                // Price
                $table->decimal('total_price', 8, 2)->nullable();

                $table->string('payment_reference')->nullable();

                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
