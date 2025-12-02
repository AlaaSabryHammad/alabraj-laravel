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
        // Assign all equipment to user 1 for profile page display
        \Illuminate\Support\Facades\DB::table('equipment')
            ->whereNull('user_id')
            ->update(['user_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove user assignments
        \Illuminate\Support\Facades\DB::table('equipment')
            ->where('user_id', 1)
            ->update(['user_id' => null]);
    }
};
