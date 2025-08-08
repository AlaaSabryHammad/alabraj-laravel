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
        // تحديث enum column لإضافة القيم الجديدة
        DB::statement("ALTER TABLE spare_parts MODIFY COLUMN source ENUM('new', 'returned', 'damaged_replacement') DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع العمود للحالة السابقة
        DB::statement("ALTER TABLE spare_parts MODIFY COLUMN source ENUM('new', 'returned') DEFAULT 'new'");
    }
};
