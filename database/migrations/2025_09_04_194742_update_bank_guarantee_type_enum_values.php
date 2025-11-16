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
        // First, update any existing data that might be using the old enum values
        DB::table('projects')->where('bank_guarantee_type', 'performance')->update(['bank_guarantee_type' => null]);
        DB::table('projects')->where('bank_guarantee_type', 'advance_payment')->update(['bank_guarantee_type' => null]);
        DB::table('projects')->where('bank_guarantee_type', 'maintenance')->update(['bank_guarantee_type' => null]);
        DB::table('projects')->where('bank_guarantee_type', 'other')->update(['bank_guarantee_type' => null]);
        
        // Drop and recreate the enum column with the correct values
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('bank_guarantee_type');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('bank_guarantee_type', ['cash', 'facilities'])->nullable()->after('bank_guarantee_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('bank_guarantee_type');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('bank_guarantee_type', ['performance', 'advance_payment', 'maintenance', 'other'])->nullable()->after('bank_guarantee_amount');
        });
    }
};
