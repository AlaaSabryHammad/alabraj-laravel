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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('site'); // site, warehouse, office, etc.
            $table->unsignedBigInteger('location_type_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('coordinates')->nullable(); // GPS coordinates
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive, under_construction
            $table->string('manager_name')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('contact_phone')->nullable();
            $table->decimal('area_size', 10, 2)->nullable(); // in square meters
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
            
            // Foreign Keys will be added later
            // $table->foreign('location_type_id')->references('id')->on('location_types')->onDelete('set null');
            // $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
