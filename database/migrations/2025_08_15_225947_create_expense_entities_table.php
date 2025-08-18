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
        Schema::create('expense_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الجهة
            $table->string('type')->default('supplier'); // نوع الجهة: supplier, contractor, government, etc.
            $table->string('contact_person')->nullable(); // الشخص المسؤول
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->string('tax_number')->nullable(); // الرقم الضريبي
            $table->string('commercial_record')->nullable(); // السجل التجاري
            $table->enum('status', ['active', 'inactive'])->default('active'); // الحالة
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_entities');
    }
};
