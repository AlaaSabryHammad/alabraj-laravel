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
        // Add 'cash' and 'facilities' to the enum so older values won't be truncated
        DB::statement("ALTER TABLE `projects` MODIFY `bank_guarantee_type` ENUM('performance','advance_payment','maintenance','other','cash','facilities') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum (beware: will fail if rows contain 'cash' or 'facilities')
        DB::statement("ALTER TABLE `projects` MODIFY `bank_guarantee_type` ENUM('performance','advance_payment','maintenance','other') NULL");
    }
};
