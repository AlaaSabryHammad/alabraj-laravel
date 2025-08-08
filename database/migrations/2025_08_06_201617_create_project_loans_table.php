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
        Schema::create('project_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 15, 2);
            $table->enum('loan_source', ['bank', 'company', 'individual', 'government', 'other']);
            $table->string('lender_name');
            $table->date('loan_date');
            $table->date('due_date')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->enum('interest_type', ['fixed', 'variable'])->nullable();
            $table->enum('loan_purpose', ['equipment', 'materials', 'wages', 'operations', 'expansion', 'other']);
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'paid', 'overdue'])->default('active');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_loans');
    }
};
