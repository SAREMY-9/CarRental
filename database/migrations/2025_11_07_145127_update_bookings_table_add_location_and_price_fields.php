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
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'pickup_location_id')) {
                    $table->foreignId('pickup_location_id')->constrained('locations')->cascadeOnDelete();
                }

                if (!Schema::hasColumn('bookings', 'dropoff_location_id')) {
                    $table->foreignId('dropoff_location_id')->nullable()->constrained('locations')->nullOnDelete();
                }

                if (!Schema::hasColumn('bookings', 'total_price')) {
                    $table->decimal('total_price', 10, 2)->nullable();
                }

                if (!Schema::hasColumn('bookings', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }

    /**
     * Reverse the migrations.
     */

        public function down(): void
        {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropConstrainedForeignId('pickup_location_id');
                $table->dropConstrainedForeignId('dropoff_location_id');
                $table->dropColumn(['total_price', 'status']);
            });
        }
        
};
