<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\FuelTruck;
use App\Models\FuelDistribution;
use App\Models\EquipmentFuelConsumption;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\FuelDistributionNotification;
use Carbon\Carbon;

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
    public function showDistributions($fuelTruck)
    {
        // Handle both model binding and ID parameter
        $fuelTruckId = $fuelTruck instanceof FuelTruck ? $fuelTruck->id : (int)$fuelTruck;

        \Log::info('FuelManagementController::showDistributions - fuelTruckId: ' . $fuelTruckId . ', type: ' . gettype($fuelTruckId));

        $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
            ->where('fuel_truck_id', $fuelTruckId)
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
        try {
            \Log::info('distributeFuel called with fuelTruckId: ' . $fuelTruck->id);
            \Log::info('Request data: ' . json_encode($request->all()));

            $validated = $request->validate([
                'target_equipment_id' => 'required|exists:equipment,id',
                'quantity' => 'required|numeric|min:0.1',
                'distribution_date' => 'required|date',
                'notes' => 'nullable|string'
            ]);

            \Log::info('Validation passed: ' . json_encode($validated));

            // Check if user is the driver of the fuel truck
            $user = Auth::user();
            if (!$user) {
                \Log::error('No authenticated user found');
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            }

            // Load employee relationship if not already loaded
            if (!$user->relationLoaded('employee')) {
                $user->load('employee');
            }

            $employeeId = $user->employee?->id;
            $driverId = $fuelTruck->equipment->driver_id;

            \Log::info('Current user: ' . $user->id . ', employee_id: ' . ($employeeId ?? 'null') . ', truck driver_id: ' . ($driverId ?? 'null'));

            if (!$employeeId || $driverId !== $employeeId) {
                \Log::warning('User not authorized to distribute from this fuel truck. User employee_id: ' . ($employeeId ?? 'null') . ', Truck driver_id: ' . ($driverId ?? 'null'));
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
                'approval_status' => 'pending',
                'notes' => $request->notes
            ]);

            \Log::info('Distribution created - ID: ' . $distribution->id . ', fuel_truck_id: ' . $distribution->fuel_truck_id . ', fuelTruck model id: ' . $fuelTruck->id);

            // Send notification to target equipment driver
            $this->notifyTargetEquipmentDriver($distribution);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل التوزيع بنجاح وتم إرسال إشعار لسائق المعدة',
                'distribution' => $distribution->load(['targetEquipment', 'distributedBy'])
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in distributeFuel: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
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
     * Cancel/Delete fuel distribution (only for pending distributions)
     */
    public function cancelDistribution(FuelDistribution $distribution)
    {
        // Only allow cancellation of pending distributions
        if ($distribution->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء توزيع تمت معالجته بالفعل'
            ], 400);
        }

        $distribution->delete();

        \Log::info('Distribution cancelled - ID: ' . $distribution->id . ', fuel_truck_id: ' . $distribution->fuel_truck_id);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء التوزيع بنجاح'
        ]);
    }

    /**
     * Add quantity to fuel truck
     */
    public function addQuantity(Request $request, FuelTruck $fuelTruck)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string'
        ]);

        $quantity = $request->quantity;

        // Check if adding the quantity would exceed capacity
        if ($fuelTruck->current_quantity + $quantity > $fuelTruck->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المراد إضافتها ('. $quantity .') تتجاوز السعة المتاحة ('. ($fuelTruck->capacity - $fuelTruck->current_quantity) .')'
            ], 400);
        }

        // Update current quantity
        $updateData = [
            'current_quantity' => $fuelTruck->current_quantity + $quantity,
        ];

        // Update notes if provided
        if ($request->has('notes') && !empty($request->notes)) {
            $updateData['notes'] = $request->notes;
        }

        $fuelTruck->update($updateData);

        Log::info('Fuel quantity added', [
            'fuel_truck_id' => $fuelTruck->id,
            'quantity_added' => $quantity,
            'new_total' => $fuelTruck->current_quantity,
            'capacity' => $fuelTruck->capacity,
            'added_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة ' . $quantity . ' لتر بنجاح',
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
     * Get fuel consumption records for driver's equipment
     */
    public function getDriverFuelConsumption()
    {
        $employeeId = Auth::user()->employee_id;

        // If user is not linked to an employee, return empty array
        if (!$employeeId) {
            return response()->json([]);
        }

        // Get equipment assigned to the current driver
        $driverEquipment = Equipment::where('driver_id', $employeeId)->pluck('id');

        // If no equipment found, return empty array
        if ($driverEquipment->isEmpty()) {
            return response()->json([]);
        }

        // Get consumption records for driver's equipment
        $consumptions = EquipmentFuelConsumption::with(['equipment', 'user', 'approvedBy'])
            ->whereIn('equipment_id', $driverEquipment)
            ->orderBy('consumption_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($consumption) {
                $consumptionDateFormatted = $consumption->consumption_date
                    ? Carbon::parse($consumption->consumption_date)->locale('ar')->isoFormat('dddd، D MMMM YYYY')
                    : null;

                $approvedAtFormatted = $consumption->approved_at
                    ? Carbon::parse($consumption->approved_at)->locale('ar')->isoFormat('dddd، D MMMM YYYY HH:mm')
                    : null;

                return [
                    'id' => $consumption->id,
                    'equipment_id' => $consumption->equipment_id,
                    'equipment_name' => $consumption->equipment->name,
                    'fuel_type' => $consumption->fuel_type,
                    'fuel_type_text' => $consumption->fuel_type_text,
                    'fuel_type_color' => $consumption->fuel_type_color,
                    'quantity' => $consumption->quantity,
                    'consumption_date' => $consumption->consumption_date,
                    'consumption_date_formatted' => $consumptionDateFormatted,
                    'approval_status' => $consumption->approval_status,
                    'approval_status_text' => $consumption->approval_status_text,
                    'approval_status_color' => $consumption->approval_status_color,
                    'notes' => $consumption->notes,
                    'user_name' => $consumption->user->name,
                    'approved_by_name' => $consumption->approvedBy?->name,
                    'approved_at' => $approvedAtFormatted
                ];
            });

        return response()->json($consumptions);
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
