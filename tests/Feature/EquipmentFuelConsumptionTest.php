<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\FuelTruck;
use App\Models\EquipmentFuelConsumption;

class EquipmentFuelConsumptionTest extends TestCase
{
    use RefreshDatabase;

    protected $fuelTruckDriver;
    protected $equipmentDriver;
    protected $fuelTruckEquipment;
    protected $equipment;
    protected $fuelTruck;

    protected function setUp(): void
    {
        parent::setUp();

        // Create fuel truck driver
        $fuelTruckDriverUser = User::factory()->create(['name' => 'Fuel Truck Driver']);
        $this->fuelTruckDriver = Employee::factory()->create([
            'user_id' => $fuelTruckDriverUser->id,
            'name' => 'Fuel Truck Driver'
        ]);

        // Create equipment driver
        $equipmentDriverUser = User::factory()->create(['name' => 'Equipment Driver']);
        $this->equipmentDriver = Employee::factory()->create([
            'user_id' => $equipmentDriverUser->id,
            'name' => 'Equipment Driver'
        ]);

        // Create fuel truck equipment
        $this->fuelTruckEquipment = Equipment::factory()->create([
            'name' => 'Fuel Truck',
            'driver_id' => $this->fuelTruckDriver->id,
            'category' => 'support_vehicle'
        ]);

        // Create fuel truck record
        $this->fuelTruck = FuelTruck::create([
            'equipment_id' => $this->fuelTruckEquipment->id,
            'fuel_type' => 'diesel',
            'capacity' => 1000,
            'current_quantity' => 500
        ]);

        // Create regular equipment
        $this->equipment = Equipment::factory()->create([
            'name' => 'Excavator',
            'driver_id' => $this->equipmentDriver->id
        ]);
    }

    /** @test */
    public function equipment_driver_can_approve_fuel_consumption()
    {
        // Create fuel consumption record by fuel truck driver
        $fuelConsumption = EquipmentFuelConsumption::create([
            'equipment_id' => $this->equipment->id,
            'user_id' => $this->fuelTruckDriver->user_id,
            'fuel_type' => 'diesel',
            'quantity' => 50,
            'consumption_date' => now(),
            'approval_status' => 'pending'
        ]);

        // Login as equipment driver
        $this->actingAs($this->equipmentDriver->user);

        // Approve fuel consumption
        $response = $this->patch("/equipment-fuel-consumption/{$fuelConsumption->id}/approve", [
            'approval_notes' => 'Approved for testing'
        ]);

        // Assert redirect back with success
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Refresh model
        $fuelConsumption->refresh();

        // Assert consumption is approved
        $this->assertEquals('approved', $fuelConsumption->approval_status);
        $this->assertEquals('Approved for testing', $fuelConsumption->approval_notes);

        // Assert fuel quantity is deducted from fuel truck
        $this->fuelTruck->refresh();
        $this->assertEquals(450, $this->fuelTruck->current_quantity);
    }

    /** @test */
    public function non_equipment_driver_cannot_approve_fuel_consumption()
    {
        // Create fuel consumption record
        $fuelConsumption = EquipmentFuelConsumption::create([
            'equipment_id' => $this->equipment->id,
            'user_id' => $this->fuelTruckDriver->user_id,
            'fuel_type' => 'diesel',
            'quantity' => 50,
            'consumption_date' => now(),
            'approval_status' => 'pending'
        ]);

        // Login as fuel truck driver (not equipment driver)
        $this->actingAs($this->fuelTruckDriver->user);

        // Try to approve fuel consumption
        $response = $this->patch("/equipment-fuel-consumption/{$fuelConsumption->id}/approve", [
            'approval_notes' => 'Trying to approve'
        ]);

        // Assert redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Refresh model
        $fuelConsumption->refresh();

        // Assert consumption is still pending
        $this->assertEquals('pending', $fuelConsumption->approval_status);
    }

    /** @test */
    public function fuel_is_not_deducted_when_approval_fails()
    {
        // Login as equipment driver
        $this->actingAs($this->equipmentDriver->user);

        // Create fuel consumption record with quantity exceeding fuel truck capacity
        $fuelConsumption = EquipmentFuelConsumption::create([
            'equipment_id' => $this->equipment->id,
            'user_id' => $this->fuelTruckDriver->user_id,
            'fuel_type' => 'diesel',
            'quantity' => 600, // More than available (500)
            'consumption_date' => now(),
            'approval_status' => 'pending'
        ]);

        // Try to approve fuel consumption
        $response = $this->patch("/equipment-fuel-consumption/{$fuelConsumption->id}/approve", [
            'approval_notes' => 'Trying to approve'
        ]);

        // Assert redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Assert fuel quantity is NOT deducted from fuel truck
        $this->fuelTruck->refresh();
        $this->assertEquals(500, $this->fuelTruck->current_quantity);
    }
}