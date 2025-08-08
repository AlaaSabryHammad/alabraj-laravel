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
            // إضافة حقول العقد
            $table->string('contract_number')->nullable()->after('supplier_id')->comment('رقم العقد');
            $table->decimal('daily_rate', 8, 2)->nullable()->after('contract_number')->comment('الأجرة اليومية');
            $table->date('contract_start_date')->nullable()->after('daily_rate')->comment('تاريخ بداية العقد');
            $table->date('contract_end_date')->nullable()->after('contract_start_date')->comment('تاريخ انتهاء العقد');

            // إزالة الحقول القديمة التي لم تعد مطلوبة
            $table->dropColumn(['loading_type', 'capacity_volume', 'capacity_weight']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_trucks', function (Blueprint $table) {
            // إزالة الحقول الجديدة
            $table->dropColumn(['contract_number', 'daily_rate', 'contract_start_date', 'contract_end_date']);

            // إعادة الحقول القديمة
            $table->enum('loading_type', ['box', 'tank'])->comment('نوع التحميل: صندوق أو تانك');
            $table->decimal('capacity_volume', 8, 2)->nullable()->comment('سعة الصندوق بالمتر المكعب');
            $table->decimal('capacity_weight', 8, 2)->nullable()->comment('سعة التانك بالطن');
        });
    }
};
