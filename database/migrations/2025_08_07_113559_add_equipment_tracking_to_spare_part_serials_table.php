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
        Schema::table('spare_part_serials', function (Blueprint $table) {
            // تتبع تعيين القطع للمعدات والموظفين
            $table->foreignId('assigned_to_equipment')->nullable()->after('status')->constrained('equipment')->onDelete('set null');
            $table->foreignId('assigned_to_employee')->nullable()->after('assigned_to_equipment')->constrained('employees')->onDelete('set null');
            $table->datetime('assignment_date')->nullable()->after('assigned_to_employee');
            
            // تتبع الإرجاع والحالة
            $table->datetime('return_date')->nullable()->after('assignment_date');
            $table->string('condition_notes')->nullable()->after('return_date');
            $table->foreignId('returned_by')->nullable()->after('condition_notes')->constrained('employees')->onDelete('set null');
            $table->foreignId('source_equipment_id')->nullable()->after('returned_by')->constrained('equipment')->onDelete('set null');
            
            // تتبع التخلص من القطع التالفة
            $table->datetime('disposal_date')->nullable()->after('source_equipment_id');
            $table->enum('disposal_method', ['scrap', 'recycle', 'repair', 'return_to_supplier'])->nullable()->after('disposal_date');
            
            $table->index(['assigned_to_equipment', 'status']);
            $table->index(['assigned_to_employee', 'assignment_date']);
            $table->index(['status', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spare_part_serials', function (Blueprint $table) {
            $table->dropForeign(['assigned_to_equipment']);
            $table->dropForeign(['assigned_to_employee']);
            $table->dropForeign(['returned_by']);
            $table->dropForeign(['source_equipment_id']);
            
            $table->dropIndex(['assigned_to_equipment', 'status']);
            $table->dropIndex(['assigned_to_employee', 'assignment_date']);
            $table->dropIndex(['status', 'location_id']);
            
            $table->dropColumn([
                'assigned_to_equipment',
                'assigned_to_employee',
                'assignment_date',
                'return_date',
                'condition_notes',
                'returned_by',
                'source_equipment_id',
                'disposal_date',
                'disposal_method',
            ]);
        });
    }
};
