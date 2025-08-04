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
        // تحديث قيم الحالة من العربية إلى الإنجليزية
        DB::table('internal_trucks')->where('status', 'متاح')->update(['status' => 'available']);
        DB::table('internal_trucks')->where('status', 'قيد الاستخدام')->update(['status' => 'in_use']);
        DB::table('internal_trucks')->where('status', 'تحت الصيانة')->update(['status' => 'maintenance']);
        DB::table('internal_trucks')->where('status', 'غير متاح')->update(['status' => 'out_of_service']);

        // تحديث نوع الوقود
        DB::table('internal_trucks')->where('fuel_type', 'ديزل')->update(['fuel_type' => 'diesel']);
        DB::table('internal_trucks')->where('fuel_type', 'بنزين')->update(['fuel_type' => 'gasoline']);
        DB::table('internal_trucks')->where('fuel_type', 'هجين')->update(['fuel_type' => 'hybrid']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // العكس - إرجاع القيم للعربية
        DB::table('internal_trucks')->where('status', 'available')->update(['status' => 'متاح']);
        DB::table('internal_trucks')->where('status', 'in_use')->update(['status' => 'قيد الاستخدام']);
        DB::table('internal_trucks')->where('status', 'maintenance')->update(['status' => 'تحت الصيانة']);
        DB::table('internal_trucks')->where('status', 'out_of_service')->update(['status' => 'غير متاح']);

        DB::table('internal_trucks')->where('fuel_type', 'diesel')->update(['fuel_type' => 'ديزل']);
        DB::table('internal_trucks')->where('fuel_type', 'gasoline')->update(['fuel_type' => 'بنزين']);
        DB::table('internal_trucks')->where('fuel_type', 'hybrid')->update(['fuel_type' => 'هجين']);
    }
};
