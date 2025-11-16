<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custody_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add custody_type_id to custodies table
        Schema::table('custodies', function (Blueprint $table) {
            $table->foreignId('custody_type_id')->nullable()->constrained()->nullOnDelete();
        });

        // Insert default custody types
        DB::table('custody_types')->insert([
            [
                'name' => 'مصروفات نثرية',
                'description' => 'عهدة للمصروفات النثرية اليومية',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'مصروفات مشروع',
                'description' => 'عهدة لمصروفات مشروع محدد',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'مصروفات سفر',
                'description' => 'عهدة لمصروفات السفر والتنقلات',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::table('custodies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('custody_type_id');
        });
        Schema::dropIfExists('custody_types');
    }
};
