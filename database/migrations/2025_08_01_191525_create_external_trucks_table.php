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
        Schema::create('external_trucks', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique()->comment('رقم اللوحة');
            $table->string('driver_name')->comment('اسم السائق');
            $table->string('driver_phone')->comment('رقم جوال السائق');
            $table->enum('loading_type', ['box', 'tank'])->comment('نوع التحميل: صندوق أو تانك');
            $table->decimal('capacity_volume', 8, 2)->nullable()->comment('سعة الصندوق بالمتر المكعب');
            $table->decimal('capacity_weight', 8, 2)->nullable()->comment('سعة التانك بالطن');

            // Contract fields
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('contract_number')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->decimal('contract_value', 12, 2)->nullable();
            $table->text('contract_terms')->nullable();

            $table->text('notes')->nullable()->comment('ملاحظات إضافية');
            $table->json('photos')->nullable()->comment('صور الشاحنة');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active')->comment('حالة الشاحنة');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_trucks');
    }
};
