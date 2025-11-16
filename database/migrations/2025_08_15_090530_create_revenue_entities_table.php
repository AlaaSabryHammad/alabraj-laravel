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
        Schema::create('revenue_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الجهة
            $table->string('type')->default('client'); // نوع الجهة: client, government, company, individual
            $table->string('contact_person')->nullable(); // الشخص المسؤول
            $table->string('phone')->nullable(); // رقم الجوال
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->string('tax_number')->nullable(); // الرقم الضريبي
            $table->string('commercial_record')->nullable(); // السجل التجاري
            $table->enum('status', ['active', 'inactive'])->default('active'); // الحالة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_entities');
    }
};