<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();

        for ($i = 0; $i < 20; $i++) {
            $manager = $employees->random();
            Project::create([
                'name' => 'Project ' . ($i + 1),
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'start_date' => now(),
                'budget' => rand(100000, 10000000),
                'location' => 'Location ' . ($i + 1),
                'status' => 'active',
            ]);
        }
    }
}
