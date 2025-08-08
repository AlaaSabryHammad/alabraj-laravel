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
        Schema::table('spare_parts', function (Blueprint $table) {
            // فحص وجود العمود أولاً
            if (!Schema::hasColumn('spare_parts', 'spare_part_type_id')) {
                $table->foreignId('spare_part_type_id')->nullable()->after('id')->constrained('spare_part_types')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('spare_parts', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('code'); // السيريال الفريد
            }
            
            if (!Schema::hasColumn('spare_parts', 'barcode')) {
                $table->string('barcode')->nullable()->after('serial_number'); // الباركود
            }
            
            if (!Schema::hasColumn('spare_parts', 'source')) {
                $table->enum('source', ['new', 'returned'])->default('new')->after('barcode'); // مصدر القطعة (جديدة أم مُعادة)
            }
            
            if (!Schema::hasColumn('spare_parts', 'returned_by_employee_id')) {
                $table->foreignId('returned_by_employee_id')->nullable()->after('source')->constrained('employees')->onDelete('set null'); // الموظف الذي أعاد القطعة
            }
            
            if (!Schema::hasColumn('spare_parts', 'return_notes')) {
                $table->text('return_notes')->nullable()->after('returned_by_employee_id'); // ملاحظات الإعادة
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('spare_part_type_id');
            $table->dropColumn(['serial_number', 'barcode', 'source', 'returned_by_employee_id', 'return_notes']);
        });
    }
};
