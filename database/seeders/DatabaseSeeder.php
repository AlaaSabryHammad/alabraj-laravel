<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // إنشاء الأدوار والصلاحيات الأساسية
            RolesAndPermissionsSeeder::class,

            // إنشاء أنواع المواقع فقط
            LocationTypeSeeder::class,

            // إنشاء أنواع المعدات
            EquipmentTypeSeeder::class,

            // إنشاء المستخدم الوحيد محمد الشهراني
            MinimalUserSeeder::class,

            // تعطيل: إنشاء المعدات
            // EquipmentSeeder::class,
        ]);
    }
}
