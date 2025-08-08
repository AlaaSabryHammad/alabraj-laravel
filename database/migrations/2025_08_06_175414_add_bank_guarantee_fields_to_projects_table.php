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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('bank_guarantee_amount', 15, 2)->nullable()->after('budget');
            $table->enum('bank_guarantee_type', ['cash', 'facilities'])->nullable()->after('bank_guarantee_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['bank_guarantee_amount', 'bank_guarantee_type']);
        });
    }
};
