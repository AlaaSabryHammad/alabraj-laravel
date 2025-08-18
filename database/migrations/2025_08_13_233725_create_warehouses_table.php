<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // no-op: real warehouses table created in an earlier migration (2025_08_13_233400)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no-op
    }
}
