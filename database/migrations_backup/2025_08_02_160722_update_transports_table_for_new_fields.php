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
        Schema::table('transports', function (Blueprint $table) {
            // Transport type and vehicle references
            $table->enum('transport_type', ['internal', 'external'])->after('id');
            $table->unsignedBigInteger('internal_vehicle_id')->nullable()->after('transport_type');
            $table->unsignedBigInteger('external_truck_id')->nullable()->after('internal_vehicle_id');

            // Material and locations
            $table->unsignedBigInteger('material_id')->nullable()->after('external_truck_id');
            $table->decimal('quantity', 10, 2)->nullable()->after('material_id');
            $table->unsignedBigInteger('loading_location_id')->nullable()->after('quantity');
            $table->unsignedBigInteger('unloading_location_id')->nullable()->after('loading_location_id');

            // Trip timing
            $table->timestamp('estimated_arrival')->nullable()->after('departure_time');

            // Notes field
            $table->text('notes')->nullable()->after('fuel_cost');

            // Add foreign key constraints
            $table->foreign('internal_vehicle_id')->references('id')->on('equipment')->onDelete('set null');
            $table->foreign('external_truck_id')->references('id')->on('external_trucks')->onDelete('set null');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
            $table->foreign('loading_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('unloading_location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['internal_vehicle_id']);
            $table->dropForeign(['external_truck_id']);
            $table->dropForeign(['material_id']);
            $table->dropForeign(['loading_location_id']);
            $table->dropForeign(['unloading_location_id']);

            // Drop columns
            $table->dropColumn([
                'transport_type',
                'internal_vehicle_id',
                'external_truck_id',
                'material_id',
                'quantity',
                'loading_location_id',
                'unloading_location_id',
                'estimated_arrival',
                'notes'
            ]);
        });
    }
};
