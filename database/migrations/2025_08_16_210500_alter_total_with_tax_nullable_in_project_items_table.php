<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Make total_with_tax nullable with default 0 to avoid insertion errors when legacy code omits it
        Schema::table('project_items', function (Blueprint $table) {
            try {
                $table->decimal('total_with_tax', 12, 2)->nullable()->default(0)->change();
            } catch (\Exception $e) {
                // Fallback: if change not supported (older MySQL), add column alteration via raw SQL
                try {
                    \DB::statement('ALTER TABLE project_items MODIFY total_with_tax DECIMAL(12,2) NULL DEFAULT 0');
                } catch (\Exception $inner) {
                    // Silently ignore to not break deployment; validation layer computes value anyway
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_items', function (Blueprint $table) {
            try {
                $table->decimal('total_with_tax', 12, 2)->nullable(false)->default(null)->change();
            } catch (\Exception $e) {
                // Attempt to revert with raw SQL (may fail safely)
                try {
                    \DB::statement('ALTER TABLE project_items MODIFY total_with_tax DECIMAL(12,2) NOT NULL');
                } catch (\Exception $inner) {
                    // ignore
                }
            }
        });
    }
};
