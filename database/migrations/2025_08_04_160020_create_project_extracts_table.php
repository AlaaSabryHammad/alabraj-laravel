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
        Schema::create('project_extracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('extract_number');
            $table->text('description')->nullable();
            $table->date('extract_date');
            $table->enum('status', ['draft', 'submitted', 'approved', 'paid'])->default('draft');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(15.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total_with_tax', 15, 2)->default(0.00);
            $table->string('file_path')->nullable();
            $table->json('items_data')->nullable();
            $table->unsignedBigInteger('revenue_voucher_id')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['project_id', 'extract_date']);
            $table->unique(['project_id', 'extract_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_extracts');
    }
};
