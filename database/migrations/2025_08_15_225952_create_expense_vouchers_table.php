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
        Schema::create('expense_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique(); // رقم السند التسلسلي
            $table->date('voucher_date'); // تاريخ السند
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('cascade'); // فئة الصرف
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null'); // الموظف المستفيد
            $table->decimal('amount', 12, 2); // المبلغ
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'other'])->default('cash'); // طريقة الصرف
            $table->text('description'); // البيان
            $table->foreignId('expense_entity_id')->nullable()->constrained('expense_entities')->onDelete('set null'); // الجهة صاحبة الصرف
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // المشروع
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null'); // الموقع
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending'); // حالة السند
            $table->text('notes')->nullable(); // ملاحظات
            $table->decimal('tax_rate', 5, 2)->default(15.00)->comment('معدل الضريبة');
            $table->decimal('tax_amount', 15, 2)->default(0.00)->comment('قيمة الضريبة');
            $table->decimal('amount_without_tax', 15, 2)->default(0.00)->comment('المبلغ بدون الضريبة');
            $table->string('reference_number')->nullable(); // رقم مرجعي
            $table->json('attachments')->nullable(); // المرفقات
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // منشئ السند
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
        Schema::dropIfExists('expense_vouchers');
    }
};
