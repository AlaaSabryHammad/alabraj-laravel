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
        Schema::create('revenue_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique(); // رقم السند التسلسلي
            $table->date('voucher_date'); // تاريخ السند
            $table->foreignId('revenue_entity_id')->nullable()->constrained('revenue_entities')->onDelete('set null'); // جهة مصدر الإيراد
            $table->decimal('amount', 15, 2); // المبلغ
            $table->text('description'); // البيان
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'other']); // نوع الصرف
            $table->enum('tax_type', ['taxable', 'non_taxable']); // نوع الضريبة
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // المشروع
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null'); // الموقع
            $table->string('attachment_path')->nullable(); // مسار الملف المرفق
            $table->text('notes')->nullable(); // ملاحظات
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending'); // الحالة
            $table->foreignId('created_by')->constrained('users'); // منشئ السند
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // معتمد السند
            $table->timestamp('approved_at')->nullable(); // تاريخ الاعتماد
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_vouchers');
    }
};