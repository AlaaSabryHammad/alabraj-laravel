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
        Schema::create('project_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->time('visit_time')->nullable();
            $table->foreignId('visitor_id')->constrained('employees')->onDelete('cascade');
            $table->string('visitor_name')->nullable(); // Additional visitor name field
            $table->enum('visit_type', ['inspection', 'meeting', 'supervision', 'coordination', 'other'])->default('inspection');
            $table->text('visit_notes');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_visits');
    }
};
