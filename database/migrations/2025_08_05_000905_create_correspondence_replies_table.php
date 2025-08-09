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
        Schema::create('correspondence_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('correspondence_id')->constrained('correspondences')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('reply_content');
            $table->enum('reply_type', ['reply', 'forward', 'note'])->default('reply');
            $table->enum('status', ['draft', 'sent'])->default('draft');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
        });

        // Add status field to correspondences table to track if replied
        Schema::table('correspondences', function (Blueprint $table) {
            $table->enum('status', ['pending', 'in_progress', 'replied', 'closed'])->default('pending')->after('priority');
            $table->timestamp('replied_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correspondences', function (Blueprint $table) {
            $table->dropColumn(['status', 'replied_at']);
        });

        Schema::dropIfExists('correspondence_replies');
    }
};
