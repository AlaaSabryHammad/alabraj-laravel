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
        Schema::create('spare_part_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spare_part_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('barcode')->unique();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['available', 'assigned', 'returned', 'damaged'])->default('available');
            $table->foreignId('assigned_to_employee_id')->nullable()->constrained('employees')->onDelete('set null');

            // Equipment tracking fields
            $table->foreignId('assigned_to_equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->string('equipment_location')->nullable();

            $table->date('assigned_date')->nullable();
            $table->date('returned_date')->nullable();

            // Export fields
            $table->foreignId('exported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('exported_at')->nullable();
            $table->text('export_notes')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['spare_part_id', 'status']);
            $table->index(['location_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_serials');
    }
};
