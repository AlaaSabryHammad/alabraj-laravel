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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('unit')->nullable();
            $table->string('unit_of_measure');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->integer('minimum_stock')->default(0);
            $table->integer('maximum_stock')->nullable();
            $table->integer('current_stock')->default(0);
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->json('specifications')->nullable();
            $table->enum('status', ['active', 'inactive', 'out_of_stock', 'discontinued'])->default('active');
            $table->string('storage_location')->nullable();
            $table->date('last_purchase_date')->nullable();
            $table->decimal('last_purchase_price', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
