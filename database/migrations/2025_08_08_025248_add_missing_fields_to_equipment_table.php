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
        Schema::table('equipment', function (Blueprint $table) {
            // إضافة الحقول المفقودة
            $table->string('category')->nullable()->after('name')->comment('فئة المعدة');
            $table->text('notes')->nullable()->after('description')->comment('ملاحظات');
            $table->foreignId('user_id')->nullable()->constrained('users')->after('notes')->comment('المستخدم الذي أضاف المعدة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            // إزالة الحقول الجديدة
            $table->dropForeign(['user_id']);
            $table->dropColumn(['category', 'notes', 'user_id']);
        });
    }
};
