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
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            // أضف عمود status لتمييز نوع المخزون (new, damaged, etc)
            $table->string('status')->default('new')->after('location_shelf');

            // أضف عمود damaged_stock لتتبع القطع التالفة
            $table->integer('damaged_stock')->default(0)->after('available_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->dropColumn(['status', 'damaged_stock']);
        });
    }
};
