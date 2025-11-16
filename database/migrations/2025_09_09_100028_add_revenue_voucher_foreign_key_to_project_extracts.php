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
        Schema::table('project_extracts', function (Blueprint $table) {
            $table->foreign('revenue_voucher_id')
                ->references('id')
                ->on('revenue_vouchers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_extracts', function (Blueprint $table) {
            $table->dropForeign(['revenue_voucher_id']);
        });
    }
};
