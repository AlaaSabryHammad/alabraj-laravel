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
        // Update status values from Arabic to English
        DB::table('internal_trucks')
            ->where('status', 'متاح')
            ->update(['status' => 'available']);

        DB::table('internal_trucks')
            ->where('status', 'قيد الاستخدام')
            ->update(['status' => 'in_use']);

        DB::table('internal_trucks')
            ->where('status', 'تحت الصيانة')
            ->update(['status' => 'maintenance']);

        DB::table('internal_trucks')
            ->where('status', 'غير متاح')
            ->update(['status' => 'out_of_service']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes - convert back to Arabic
        DB::table('internal_trucks')
            ->where('status', 'available')
            ->update(['status' => 'متاح']);

        DB::table('internal_trucks')
            ->where('status', 'in_use')
            ->update(['status' => 'قيد الاستخدام']);

        DB::table('internal_trucks')
            ->where('status', 'maintenance')
            ->update(['status' => 'تحت الصيانة']);

        DB::table('internal_trucks')
            ->where('status', 'out_of_service')
            ->update(['status' => 'غير متاح']);
    }
};
