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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المورد
            $table->string('company_name')->nullable(); // اسم الشركة
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('phone'); // رقم الهاتف
            $table->string('phone_2')->nullable(); // رقم هاتف إضافي
            $table->text('address')->nullable(); // العنوان
            $table->string('city')->nullable(); // المدينة
            $table->string('country')->default('المملكة العربية السعودية'); // البلد
            $table->string('tax_number')->nullable(); // الرقم الضريبي
            $table->string('cr_number')->nullable(); // رقم السجل التجاري
            $table->string('category')->nullable(); // فئة المورد (مواد بناء، معدات، خدمات، إلخ)
            $table->enum('payment_terms', ['نقدي', 'آجل 30 يوم', 'آجل 60 يوم', 'آجل 90 يوم'])->default('نقدي'); // شروط الدفع
            $table->decimal('credit_limit', 15, 2)->default(0); // حد الائتمان
            $table->text('notes')->nullable(); // ملاحظات
            $table->enum('status', ['نشط', 'غير نشط', 'معلق'])->default('نشط'); // الحالة
            $table->string('contact_person')->nullable(); // الشخص المسؤول
            $table->string('contact_person_phone')->nullable(); // هاتف الشخص المسؤول
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
