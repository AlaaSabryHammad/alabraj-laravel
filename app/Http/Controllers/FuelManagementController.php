<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\FuelTruck;
use App\Models\FuelDistribution;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\FuelDistributionNotification;

class FuelManagementController extends Controller
{
    /**
     * Display fuel trucks listing
     */
    public function index()
    {
        // Get equipment of type fuel truck (تانكر دعم الديزل)
        $fuelTrucksEquipment = Equipment::with(['fuelTruck', 'driver', 'location'])
            ->where(function ($query) {
                $query->where('type', 'LIKE', '%تانكر%')
                    ->orWhere('type', 'LIKE', '%محروقات%')
                    ->orWhere('type', 'LIKE', '%وقود%');
            })
            ->get();

        // Calculate summary statistics
        $totalTrucks = $fuelTrucksEquipment->count();
        $totalCapacity = $fuelTrucksEquipment->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->capacity : 0;
        });
        $totalCurrent = $fuelTrucksEquipment->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->current_quantity : 0;
        });
        $totalRemaining = $fuelTrucksEquipment->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->remaining_quantity : 0;
        });

        $summary = [
            'total_trucks' => $totalTrucks,
            'total_capacity' => $totalCapacity,
            'total_current' => $totalCurrent,
            'total_remaining' => $totalRemaining,
            'utilization_percentage' => $totalCapacity > 0 ? ($totalCurrent / $totalCapacity) * 100 : 0
        ];

        return view('fuel-management.index', compact('fuelTrucksEquipment', 'summary'));
    }

    /**
     * Add fuel to truck
     */
    public function addFuel(Request $request, Equipment $equipment)
    {
        $request->validate([
            'fuel_type' => 'required|in:diesel,gasoline,engine_oil,hydraulic_oil,radiator_water,brake_oil,other',
            'capacity' => 'required|numeric|min:0',
            'current_quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Create or update fuel truck record
        $fuelTruck = FuelTruck::updateOrCreate(
            ['equipment_id' => $equipment->id],
            [
                'fuel_type' => $request->fuel_type,
                'capacity' => $request->capacity,
                'current_quantity' => $request->current_quantity,
                'notes' => $request->notes
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المحروقات بنجاح',
            'fuel_truck' => $fuelTruck
        ]);
    }

    /**
     * Show fuel distributions for a truck
     */
    public function showDistributions(FuelTruck $fuelTruck)
    {
        $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
            ->where('fuel_truck_id', $fuelTruck->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($distribution) {
                return [
                    'id' => $distribution->id,
                    'quantity' => $distribution->quantity,
                    'distribution_date' => $distribution->distribution_date,
                    'distribution_date_formatted' => $distribution->distribution_date_formatted,
                    'approval_status' => $distribution->approval_status,
                    'approval_status_text' => $distribution->approval_status_text,
                    'approval_status_color' => $distribution->approval_status_color,
                    'notes' => $distribution->notes,
                    'target_equipment' => [
                        'id' => $distribution->targetEquipment->id,
                        'name' => $distribution->targetEquipment->name
                    ],
                    'distributed_by' => [
                        'id' => $distribution->distributedBy->id,
                        'name' => $distribution->distributedBy->name
                    ],
                    'approved_by' => $distribution->approvedBy ? [
                        'id' => $distribution->approvedBy->id,
                        'name' => $distribution->approvedBy->name
                    ] : null
                ];
            });

        return response()->json($distributions);
    }

    /**
     * Distribute fuel to equipment
     */
    public function distributeFuel(Request $request, FuelTruck $fuelTruck)
    {
        $request->validate([
            'target_equipment_id' => 'required|exists:equipments,id',
            'quantity' => 'required|numeric|min:0.1',
            'distribution_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Check if user is the driver of the fuel truck
        $user = Auth::user();
        if (!$user->employee || $fuelTruck->equipment->driver_id !== $user->employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتوزيع المحروقات من هذه السيارة'
            ], 403);
        }

        // Check if there's enough fuel
        if ($request->quantity > $fuelTruck->remaining_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة أكبر من الكمية المتاحة'
            ], 400);
        }

        $distribution = FuelDistribution::create([
            'fuel_truck_id' => $fuelTruck->id,
            'target_equipment_id' => $request->target_equipment_id,
            'distributed_by' => Auth::id(),
            'fuel_type' => $fuelTruck->fuel_type,
            'quantity' => $request->quantity,
            'distribution_date' => $request->distribution_date,
            'notes' => $request->notes
        ]);

        // Send notification to target equipment driver
        $this->notifyTargetEquipmentDriver($distribution);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل التوزيع بنجاح وتم إرسال إشعار لسائق المعدة',
            'distribution' => $distribution->load(['targetEquipment', 'distributedBy'])
        ]);
    }

    /**
     * Approve fuel distribution
     */
    public function approveDistribution(Request $request, FuelDistribution $distribution)
    {
        $request->validate([
            'approval_notes' => 'nullable|string'
        ]);

        $distribution->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم اعتماد التوزيع بنجاح'
        ]);
    }

    /**
     * Reject fuel distribution
     */
    public function rejectDistribution(Request $request, FuelDistribution $distribution)
    {
        $request->validate([
            'approval_notes' => 'required|string'
        ]);

        $distribution->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم رفض التوزيع'
        ]);
    }

    /**
     * Add quantity to fuel truck
     */
    public function addQuantity(Request $request, FuelTruck $fuelTruck)
    {
        $request->validate([
            'fuel_type' => 'required|in:diesel,gasoline,engine_oil,hydraulic_oil,radiator_water,brake_oil,other',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        $quantity = $request->quantity;

        // Check if adding the quantity would exceed capacity
        if ($fuelTruck->current_quantity + $quantity > $fuelTruck->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المراد إضافتها تتجاوز السعة الكلية للتانكر'
            ], 400);
        }

        // Update current quantity
        $fuelTruck->update([
            'current_quantity' => $fuelTruck->current_quantity + $quantity,
            'fuel_type' => $request->fuel_type,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الكمية بنجاح',
            'fuel_truck' => $fuelTruck
        ]);
    }

    /**
     * Driver distribution page - for fuel truck drivers
     */
    public function driverIndex()
    {
        // Get fuel trucks assigned to current user (driver)
        $driverFuelTrucks = Equipment::with(['fuelTruck', 'location'])
            ->whereHas('fuelTruck')
            ->where('driver_id', Auth::user()->employee_id)
            ->get();

        // Get all other equipment for distribution targets
        $targetEquipments = Equipment::where('id', '!=', function ($query) {
            $query->select('equipment_id')
                ->from('fuel_trucks')
                ->where('equipment_id', '!=', null);
        })
            ->where('status', '!=', 'out_of_order')
            ->with(['location'])
            ->get();

        return view('fuel-management.driver', compact('driverFuelTrucks', 'targetEquipments'));
    }

    /**
     * إرسال إشعار لسائق المعدة المستهدفة بتوزيع المحروقات
     */
    private function notifyTargetEquipmentDriver(FuelDistribution $distribution): void
    {
        try {
            // الحصول على المعدة المستهدفة والسائق
            $equipment = Equipment::with('driver.user')->find($distribution->target_equipment_id);

            if (!$equipment || !$equipment->driver || !$equipment->driver->user) {
                return;
            }

            // إرسال إشعار لسائق المعدة
            $equipment->driver->user->notify(new FuelDistributionNotification($distribution));
        } catch (\Exception $e) {
            Log::info('Failed to send fuel distribution notification to driver: ' . $e->getMessage());
        }
    }
}
