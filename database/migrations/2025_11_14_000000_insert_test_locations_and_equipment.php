<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert test locations
        DB::table('locations')->insert([
            [
                'id' => 1,
                'name' => 'الموقع الرئيسي - الرياض',
                'type' => 'site',
                'address' => 'الرياض',
                'city' => 'الرياض',
                'region' => 'منطقة الرياض',
                'status' => 'active',
                'manager_name' => 'إدارة الموقع',
                'contact_phone' => '0501234567',
                'area_size' => 5000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'الموقع الثانوي - جدة',
                'type' => 'site',
                'address' => 'جدة',
                'city' => 'جدة',
                'region' => 'منطقة مكة',
                'status' => 'active',
                'manager_name' => 'إدارة الموقع',
                'contact_phone' => '0502234567',
                'area_size' => 3000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'المستودع الرئيسي',
                'type' => 'warehouse',
                'address' => 'الرياض',
                'city' => 'الرياض',
                'region' => 'منطقة الرياض',
                'status' => 'active',
                'manager_name' => 'إدارة المستودع',
                'contact_phone' => '0503234567',
                'area_size' => 8000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'موقع الاختبار',
                'type' => 'site',
                'address' => 'موقع اختبار',
                'city' => 'الرياض',
                'region' => 'منطقة الرياض',
                'status' => 'active',
                'manager_name' => 'إدارة الموقع',
                'contact_phone' => '0504234567',
                'area_size' => 2000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('locations')->whereIn('id', [1, 2, 3, 4])->delete();
    }
};
