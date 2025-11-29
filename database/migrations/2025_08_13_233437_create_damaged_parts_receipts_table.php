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
        Schema::create('damaged_parts_receipts', function (Blueprint $table) {
            $table->id();

            // معلومات الاستلام الأساسية
            $table->string('receipt_number')->unique()->comment('رقم إيصال الاستلام');
            $table->date('receipt_date')->comment('تاريخ الاستلام');
            $table->time('receipt_time')->comment('وقت الاستلام');

            // ربط بالمشروع والمعدة
            $table->foreignId('project_id')->constrained('projects')->comment('المشروع المرسل للقطع التالفة');
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->comment('المعدة التي أخذت منها القطعة');

            // ربط بقطعة الغيار الأصلية
            $table->foreignId('spare_part_id')->constrained('spare_parts')->comment('قطعة الغيار التالفة');
            $table->integer('quantity_received')->comment('الكمية المستلمة');

            // تصنيف حالة القطعة التالفة
            $table->enum('damage_condition', [
                'repairable',           // قابلة للإصلاح
                'non_repairable',       // غير قابلة للإصلاح  
                'replacement_needed',   // تحتاج لاستبدال
                'for_evaluation'        // تحتاج لتقييم
            ])->comment('حالة القطعة التالفة');

            // وصف التلف والأسباب
            $table->text('damage_description')->nullable()->comment('وصف التلف');
            $table->text('damage_cause')->nullable()->comment('سبب التلف');
            $table->text('technician_notes')->nullable()->comment('ملاحظات الفني');

            // معلومات المستلم والمرسل
            $table->foreignId('received_by')->constrained('employees')->comment('موظف الاستلام');
            $table->foreignId('sent_by')->nullable()->constrained('employees')->comment('موظف الإرسال من المشروع');

            // معلومات التخزين
            $table->foreignId('warehouse_id')->constrained('locations')->comment('المخزن المستقبل');
            $table->string('storage_location')->nullable()->comment('موقع التخزين في المخزن');

            // التكاليف المقدرة
            $table->decimal('estimated_repair_cost', 10, 2)->nullable()->comment('التكلفة المقدرة للإصلاح');
            $table->decimal('replacement_cost', 10, 2)->nullable()->comment('تكلفة الاستبدال');

            // حالة المعالجة
            $table->enum('processing_status', [
                'received',        // تم الاستلام
                'under_evaluation', // تحت التقييم
                'approved_repair', // موافقة على الإصلاح
                'approved_replace', // موافقة على الاستبدال
                'disposed',        // تم التخلص منها
                'returned_fixed'   // تم إرجاعها بعد الإصلاح
            ])->default('received')->comment('حالة معالجة القطعة');

            // مرفقات وصور
            $table->json('photos')->nullable()->comment('صور القطعة التالفة');
            $table->json('documents')->nullable()->comment('مستندات مرفقة');

            // تواريخ مهمة
            $table->timestamp('evaluation_date')->nullable()->comment('تاريخ التقييم');
            $table->timestamp('decision_date')->nullable()->comment('تاريخ اتخاذ القرار');
            $table->timestamp('completion_date')->nullable()->comment('تاريخ إكمال المعالجة');

            $table->timestamps();

            // إنشاء فهارس للبحث السريع
            $table->index(['receipt_date', 'project_id']);
            $table->index(['processing_status', 'warehouse_id']);
            $table->index(['spare_part_id', 'damage_condition']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_parts_receipts');
    }
};
