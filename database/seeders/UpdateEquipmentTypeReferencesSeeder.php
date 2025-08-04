<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\DB;

class UpdateEquipmentTypeReferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping old type strings to new equipment type names
        $typeMapping = [
            'حفارة' => 'حفارات',
            'حفار' => 'حفارات',
            'excavator' => 'حفارات',
            'bulldozer' => 'بلدوزر',
            'بلدوزر' => 'بلدوزر',
            'crane' => 'رافعات',
            'رافعة' => 'رافعات',
            'truck' => 'شاحنات',
            'شاحنة' => 'شاحنات',
            'concrete mixer' => 'معدات خرسانة',
            'خلاطة' => 'معدات خرسانة',
            'welder' => 'معدات لحام',
            'لحام' => 'معدات لحام',
            'generator' => 'مولدات كهرباء',
            'مولد' => 'مولدات كهرباء',
            'compressor' => 'ضواغط هواء',
            'ضاغط' => 'ضواغط هواء',
            'tool' => 'أدوات يدوية',
            'أداة' => 'أدوات يدوية',
            'survey' => 'معدات مساحة',
            'مساحة' => 'معدات مساحة'
        ];

        // Get all equipment records
        $equipments = Equipment::whereNull('type_id')->get();

        foreach ($equipments as $equipment) {
            $equipmentTypeName = null;

            // Try to find matching equipment type
            foreach ($typeMapping as $oldType => $newType) {
                if (stripos($equipment->type, $oldType) !== false) {
                    $equipmentTypeName = $newType;
                    break;
                }
            }

            // If no mapping found, use the original type or default to 'أدوات يدوية'
            if (!$equipmentTypeName) {
                $equipmentTypeName = 'أدوات يدوية'; // Default fallback
            }

            // Find the equipment type ID
            $equipmentType = EquipmentType::where('name', $equipmentTypeName)->first();

            if ($equipmentType) {
                $equipment->update(['type_id' => $equipmentType->id]);
                $this->command->info("Updated equipment '{$equipment->name}' with type '{$equipmentTypeName}'");
            } else {
                $this->command->warn("Equipment type '{$equipmentTypeName}' not found for equipment '{$equipment->name}'");
            }
        }

        $this->command->info('Equipment type references updated successfully!');
    }
}
