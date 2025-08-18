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
        Schema::create('fuel_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_truck_id')->constrained()->onDelete('cascade');
            $table->foreignId('target_equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->foreignId('distributed_by')->constrained('users')->onDelete('cascade')->comment('سائق سيارة المحروقات');
            $table->enum('fuel_type', ['diesel', 'gasoline', 'engine_oil', 'hydraulic_oil', 'radiator_water', 'brake_oil', 'other']);
            $table->decimal('quantity', 10, 2);
            $table->date('distribution_date');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_distributions');
    }
};
