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
        Schema::create('correspondences', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['incoming', 'outgoing']); // وارد أو صادر
            $table->string('reference_number')->unique(); // الرقم التسلسلي التلقائي
            $table->string('external_number')->nullable(); // رقم الوارد/الصادر الخارجي
            $table->string('subject'); // موضوع المراسلة
            $table->string('from_entity')->nullable(); // جهة الإصدار (للوارد)
            $table->string('to_entity')->nullable(); // الجهة المرسل إليها (للصادر)
            $table->date('correspondence_date'); // تاريخ المراسلة
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium'); // درجة الأهمية
            $table->text('notes')->nullable(); // ملاحظات
            $table->string('file_path')->nullable(); // مسار الملف المرفق
            $table->string('file_name')->nullable(); // اسم الملف الأصلي
            $table->unsignedBigInteger('file_size')->nullable(); // حجم الملف
            $table->string('file_type')->nullable(); // نوع الملف

            // العلاقات
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // المشروع (اختياري)
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->onDelete('set null'); // المسؤول الموجه إليه (للوارد)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // المستخدم الذي سجل المعاملة

            $table->timestamps();

            // إضافة فهارس
            $table->index(['type', 'correspondence_date']);
            $table->index('reference_number');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correspondences');
    }
};
