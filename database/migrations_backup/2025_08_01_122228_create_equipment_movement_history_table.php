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
        Schema::create('equipment_movement_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('from_location_text')->nullable(); // للمواقع النصية القديمة
            $table->string('to_location_text')->nullable(); // للمواقع النصية الجديدة
            $table->foreignId('moved_by')->nullable()->constrained('users')->onDelete('set null'); // المستخدم الذي نقل المعدة
            $table->datetime('moved_at'); // تاريخ النقل
            $table->text('movement_reason')->nullable(); // سبب النقل
            $table->text('notes')->nullable(); // ملاحظات إضافية
            $table->enum('movement_type', ['location_change', 'initial_assignment', 'maintenance', 'disposal', 'other'])->default('location_change');
            $table->timestamps();

            // Index for better performance
            $table->index(['equipment_id', 'moved_at']);
            $table->index(['from_location_id', 'moved_at']);
            $table->index(['to_location_id', 'moved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_movement_history');
    }
};
