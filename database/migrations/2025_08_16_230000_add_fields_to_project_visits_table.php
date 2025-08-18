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
        Schema::table('project_visits', function (Blueprint $table) {
            // إضافة حقول جديدة إذا لم تكن موجودة
            if (!Schema::hasColumn('project_visits', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_visits', function (Blueprint $table) {
            if (Schema::hasColumn('project_visits', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
