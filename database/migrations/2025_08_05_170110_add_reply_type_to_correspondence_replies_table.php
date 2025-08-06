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
        Schema::table('correspondence_replies', function (Blueprint $table) {
            $table->enum('reply_type', ['internal', 'on_behalf'])->default('internal')->after('reply_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correspondence_replies', function (Blueprint $table) {
            $table->dropColumn('reply_type');
        });
    }
};
