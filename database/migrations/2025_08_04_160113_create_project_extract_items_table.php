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
        Schema::create('project_extract_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_extract_id')->constrained()->onDelete('cascade');
            $table->integer('project_item_index');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_value', 15, 2);
            $table->timestamps();

            $table->index(['project_extract_id', 'project_item_index'], 'pei_extract_item_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_extract_items');
    }
};
