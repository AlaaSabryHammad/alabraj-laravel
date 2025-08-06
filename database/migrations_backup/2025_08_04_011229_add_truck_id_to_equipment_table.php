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
            $table->foreignId('truck_id')->nullable()->constrained('internal_trucks')->onDelete('set null');
            $table->string('category')->nullable()->after('type');
            $table->text('notes')->nullable()->after('description');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['truck_id']);
            $table->dropColumn(['truck_id', 'category', 'notes', 'user_id']);
        });
    }
};
