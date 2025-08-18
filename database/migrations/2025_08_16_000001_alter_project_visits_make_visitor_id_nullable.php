<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('project_visits')) {
            return; // table missing
        }
        Schema::table('project_visits', function (Blueprint $table) {
            try {
                $table->dropForeign('project_visits_visitor_id_foreign');
            } catch (\Throwable $e) {
                // ignore if already dropped
            }
        });
        try {
            DB::statement('ALTER TABLE project_visits MODIFY visitor_id BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            try {
                Schema::table('project_visits', function (Blueprint $table) {
                    if (Schema::hasColumn('project_visits', 'visitor_id')) {
                        $table->unsignedBigInteger('visitor_id')->nullable()->change();
                    }
                });
            } catch (\Throwable $e2) {
                // ignore
            }
        }
        Schema::table('project_visits', function (Blueprint $table) {
            try {
                $table->foreign('visitor_id')->references('id')->on('employees')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('project_visits')) {
            return;
        }
        Schema::table('project_visits', function (Blueprint $table) {
            try {
                $table->dropForeign('project_visits_visitor_id_foreign');
            } catch (\Throwable $e) {
            }
        });
        try {
            DB::statement('ALTER TABLE project_visits MODIFY visitor_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
        }
        Schema::table('project_visits', function (Blueprint $table) {
            try {
                $table->foreign('visitor_id')->references('id')->on('employees')->onDelete('cascade');
            } catch (\Throwable $e) {
            }
        });
    }
};
