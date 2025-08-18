<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder معطل - لا ينشئ أي معدات
 * تم إبقاء الملف للمرجعية فقط
 */
class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // تم تعطيل هذا الـ seeder
        $this->command->info("⚠️ EquipmentSeeder معطل - لا يتم إنشاء أي معدات");
        return;
    }
}
