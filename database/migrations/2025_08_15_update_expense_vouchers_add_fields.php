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
        Schema::table('expense_vouchers', function (Blueprint $table) {
            $table->enum('tax_type', ['taxable', 'non_taxable'])->after('payment_method')->comment('نوع الضريبة');
            $table->string('attachment_path')->nullable()->after('notes')->comment('مسار الملف المرفق');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_vouchers', function (Blueprint $table) {
            $table->dropColumn(['tax_type', 'attachment_path']);
        });
    }
};