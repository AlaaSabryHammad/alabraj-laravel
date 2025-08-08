<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LocationTypeSeeder::class,
            LocationSeeder::class,
            EquipmentTypeSeeder::class,
            SupplierSeeder::class,
            EmployeeSeeder::class,
            EquipmentSeeder::class,
            DocumentSeeder::class,
            TransportSeeder::class,
            FinanceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
