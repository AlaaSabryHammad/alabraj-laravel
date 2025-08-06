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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('government_entity')->nullable();
            $table->string('consulting_office')->nullable();
            $table->string('project_scope')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('budget', 12, 2);
            $table->string('location');
            $table->string('project_manager')->nullable();
            $table->unsignedBigInteger('project_manager_id')->nullable();
            $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->integer('progress')->default(0);
            $table->timestamps();

            // Foreign keys will be added later
            // $table->foreign('project_manager_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
