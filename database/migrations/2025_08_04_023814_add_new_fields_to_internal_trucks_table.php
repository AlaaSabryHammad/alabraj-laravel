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
        Schema::table('internal_trucks', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->date('purchase_date')->nullable()->after('status');
            $table->decimal('purchase_price', 10, 2)->nullable()->after('purchase_date');
            $table->date('warranty_expiry')->nullable()->after('purchase_price');
            $table->date('last_maintenance')->nullable()->after('warranty_expiry');
            $table->text('description')->nullable()->after('notes');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null')->after('description');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null')->after('driver_id');

            // تحديث أنواع البيانات الموجودة
            $table->date('license_expiry')->nullable()->change();
            $table->date('insurance_expiry')->nullable()->change();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'out_of_service'])->default('available')->change();
            $table->enum('fuel_type', ['gasoline', 'diesel', 'hybrid'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_trucks', function (Blueprint $table) {
            // حذف الحقول المضافة
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
        });
    }
};
