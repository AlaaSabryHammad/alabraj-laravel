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
        Schema::create('equipment_driver_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null'); // المستخدم الذي قام بالتعيين
            $table->datetime('assigned_at'); // تاريخ بداية القيادة
            $table->datetime('released_at')->nullable(); // تاريخ نهاية القيادة
            $table->text('assignment_notes')->nullable(); // ملاحظات التعيين
            $table->text('release_notes')->nullable(); // ملاحظات التسليم
            $table->enum('status', ['active', 'completed', 'terminated'])->default('active');
            $table->timestamps();

            // Index for better performance
            $table->index(['equipment_id', 'assigned_at']);
            $table->index(['driver_id', 'assigned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_driver_history');
    }
};
