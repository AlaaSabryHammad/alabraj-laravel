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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_number')->nullable()->after('id');
            $table->string('government_entity')->nullable()->after('client_name');
            $table->string('consulting_office')->nullable()->after('government_entity');
            $table->string('project_scope')->nullable()->after('consulting_office');
            $table->unsignedBigInteger('project_manager_id')->nullable()->after('project_manager');
            $table->foreign('project_manager_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
            $table->dropColumn([
                'project_number',
                'government_entity',
                'consulting_office',
                'project_scope',
                'project_manager_id'
            ]);
        });
    }
};
