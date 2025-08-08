<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internal_trucks', function (Blueprint $table) {
            // إضافة الحقول المفقودة
            $table->date('purchase_date')->nullable()->after('status')->comment('تاريخ الشراء');
            $table->decimal('purchase_price', 10, 2)->nullable()->after('purchase_date')->comment('سعر الشراء');
            $table->date('warranty_expiry')->nullable()->after('purchase_price')->comment('تاريخ انتهاء الضمان');
            $table->date('last_maintenance')->nullable()->after('warranty_expiry')->comment('تاريخ آخر صيانة');
            $table->text('description')->nullable()->after('last_maintenance')->comment('الوصف');
            $table->foreignId('driver_id')->nullable()->constrained('employees')->after('description')->comment('السائق');
            $table->foreignId('location_id')->nullable()->constrained('locations')->after('driver_id')->comment('الموقع');

            // تحديث الحقول الموجودة
            $table->renameColumn('last_maintenance_date', 'old_last_maintenance_date');
        });

        // نسخ البيانات من العمود القديم إلى الجديد إن وُجدت
        DB::statement('UPDATE internal_trucks SET last_maintenance = old_last_maintenance_date WHERE old_last_maintenance_date IS NOT NULL');

        // حذف العمود القديم
        Schema::table('internal_trucks', function (Blueprint $table) {
            $table->dropColumn('old_last_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_trucks', function (Blueprint $table) {
            // إزالة الحقول الجديدة
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn([
                'purchase_date',
                'purchase_price',
                'warranty_expiry',
                'last_maintenance',
                'description',
                'driver_id',
                'location_id'
            ]);

            // إعادة الحقل القديم
            $table->date('last_maintenance_date')->nullable();
        });
    }
};
