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
        // Disabled: test locations and equipment data
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('locations')->whereIn('id', [1, 2, 3, 4])->delete();
    }
};
