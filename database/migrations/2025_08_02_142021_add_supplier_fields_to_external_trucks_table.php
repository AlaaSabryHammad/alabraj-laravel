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
        Schema::table('external_trucks', function (Blueprint $table) {
            // إضافة حقول المورد
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null')->comment('معرف المورد');
            $table->string('contract_number')->nullable()->comment('رقم العقد/الاتفاقية');
            $table->decimal('daily_rate', 10, 2)->nullable()->comment('الأجر اليومي');
            $table->date('contract_start_date')->nullable()->comment('تاريخ بداية التعاقد');
            $table->date('contract_end_date')->nullable()->comment('تاريخ انتهاء التعاقد');

            // إزالة حقول نوع التحميل والسعة (جعلها nullable أولاً)
            $table->enum('loading_type', ['box', 'tank'])->nullable()->change();
            $table->decimal('capacity_volume', 8, 2)->nullable()->change();
            $table->decimal('capacity_weight', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_trucks', function (Blueprint $table) {
            // إزالة حقول المورد
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'supplier_id',
                'contract_number',
                'daily_rate',
                'contract_start_date',
                'contract_end_date'
            ]);

            // إعادة حقول نوع التحميل والسعة
            $table->enum('loading_type', ['box', 'tank'])->nullable(false)->change();
        });
    }
};
