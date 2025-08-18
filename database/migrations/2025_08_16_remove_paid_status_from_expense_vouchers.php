<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update any 'paid' status to 'approved'
        DB::table('expense_vouchers')
            ->where('status', 'paid')
            ->update(['status' => 'approved']);
    }

    public function down()
    {
        // No need for down migration as we don't want to revert approved vouchers back to paid
    }
};
