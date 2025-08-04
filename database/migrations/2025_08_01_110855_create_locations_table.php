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
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('coordinates')->nullable(); // GPS coordinates
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive, under_construction
            $table->string('manager_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->decimal('area_size', 10, 2)->nullable(); // in square meters
            $table->timestamps();
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
