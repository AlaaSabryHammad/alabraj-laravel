<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\FuelDistribution;
use App\Models\EquipmentFuelConsumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FuelManagementUnifiedController extends Controller
{
    /**
     * Display unified fuel management page
     */
    public function index()
    {
        // Get all fuel trucks equipment with their details
        $fuelTrucks = Equipment::with(['fuelTruck', 'location', 'driver'])
            ->where(function ($query) {
                $query->where('type', 'LIKE', '%تانكر%')
                    ->orWhere('type', 'LIKE', '%محروقات%')
                    ->orWhere('type', 'LIKE', '%وقود%');
            })
            ->get();

        // Get all other equipment for distribution targets
        $targetEquipments = Equipment::where(function ($query) {
                $query->where('type', 'NOT LIKE', '%تانكر%')
                    ->where('type', 'NOT LIKE', '%محروقات%')
                    ->where('type', 'NOT LIKE', '%وقود%');
            })
            ->where('status', '!=', 'out_of_order')
            ->with(['location'])
            ->get();

        // Calculate summary statistics
        $totalTrucks = $fuelTrucks->count();
        $totalCapacity = $fuelTrucks->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->capacity : 0;
        });
        $totalCurrent = $fuelTrucks->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->current_quantity : 0;
        });
        $totalRemaining = $fuelTrucks->sum(function ($equipment) {
            return $equipment->fuelTruck ? $equipment->fuelTruck->remaining_quantity : 0;
        });

        $summary = [
            'total_trucks' => $totalTrucks,
            'total_capacity' => $totalCapacity,
            'total_current' => $totalCurrent,
            'total_remaining' => $totalRemaining,
            'utilization_percentage' => $totalCapacity > 0 ? ($totalCurrent / $totalCapacity) * 100 : 0
        ];

        return view('fuel-management.unified', compact('fuelTrucks', 'targetEquipments', 'summary'));
    }

    /**
     * Get fuel truck details with its distributions and consumptions
     */
    public function getTruckDetails($truckId)
    {
        try {
            $truck = Equipment::with(['fuelTruck', 'location', 'driver'])
                ->findOrFail($truckId);

            if (!$truck->fuelTruck) {
                return response()->json([
                    'error' => 'Fuel truck not found for this equipment'
                ], 404);
            }

            $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
                ->where('fuel_truck_id', $truck->fuelTruck->id)
                ->orderBy('distribution_date', 'desc')
                ->get()
                ->map(function ($dist) {
                    return [
                        'id' => $dist->id,
                        'type' => 'distribution',
                        'equipment_name' => $dist->targetEquipment->name,
                        'quantity' => (float) $dist->quantity,
                        'date' => $dist->distribution_date,
                        'date_formatted' => $dist->distribution_date?->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
                        'status' => $dist->approval_status,
                        'status_text' => $dist->approval_status_text,
                        'status_color' => $dist->approval_status_color,
                        'notes' => $dist->notes,
                        'created_by' => $dist->distributedBy->name
                    ];
                });

            return response()->json([
                'truck' => [
                    'id' => $truck->id,
                    'name' => $truck->name,
                    'fuel_type' => $truck->fuelTruck->fuel_type_text,
                    'capacity' => (float) $truck->fuelTruck->capacity,
                    'current_quantity' => (float) $truck->fuelTruck->current_quantity,
                    'remaining_quantity' => (float) $truck->fuelTruck->remaining_quantity,
                    'percentage' => $truck->fuelTruck->capacity > 0
                        ? ($truck->fuelTruck->remaining_quantity / $truck->fuelTruck->capacity) * 100
                        : 0
                ],
                'distributions' => $distributions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fuel consumption report
     */
    public function consumptionReport(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::createFromFormat('Y-m-d', $request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->has('end_date') ? Carbon::createFromFormat('Y-m-d', $request->get('end_date')) : now()->endOfMonth();

        // Get all consumption records for display in table (both approved and rejected)
        $consumptions = EquipmentFuelConsumption::with(['equipment', 'user'])
            ->whereBetween('consumption_date', [$startDate, $endDate])
            ->orderBy('consumption_date', 'desc')
            ->get()
            ->map(function ($consumption) {
                // Find the source fuel truck using fuel_truck_id first (new approach), then fallback to old logic
                $fuelTruckName = '-';

                // First, try to use the fuel_truck_id directly from consumption record
                if ($consumption->fuel_truck_id) {
                    $fuelTruckEquipment = Equipment::find($consumption->fuel_truck_id);
                    if ($fuelTruckEquipment) {
                        $fuelTruckName = $fuelTruckEquipment->name;
                    }
                }

                // Fallback: Use old logic if fuel_truck_id is not set
                if ($fuelTruckName === '-') {
                    if ($consumption->equipment->fuelTruck) {
                        // Equipment is itself a fuel truck
                        $fuelTruckName = $consumption->equipment->name;
                    } else {
                        // Equipment is not a fuel truck, find which fuel truck distributed to it
                        $distribution = \App\Models\FuelDistribution::where('target_equipment_id', $consumption->equipment->id)
                            ->whereIn('approval_status', ['approved', 'pending'])
                            ->latest('distribution_date')
                            ->first();

                        if ($distribution && $distribution->fuelTruck && $distribution->fuelTruck->equipment) {
                            $fuelTruckName = $distribution->fuelTruck->equipment->name;
                        }
                    }
                }

                return [
                    'id' => $consumption->id,
                    'equipment_name' => $consumption->equipment->name,
                    'fuel_truck_name' => $fuelTruckName,
                    'fuel_type' => $consumption->fuel_type_text,
                    'quantity' => $consumption->quantity,
                    'consumption_date' => $consumption->consumption_date,
                    'date_formatted' => $consumption->consumption_date?->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
                    'status' => $consumption->approval_status_text,
                    'status_color' => $consumption->approval_status_color,
                    'user_name' => $consumption->user->name,
                    'notes' => $consumption->notes,
                    'approval_status' => $consumption->approval_status
                ];
            });

        // Get only approved records for calculations
        $approvedConsumptions = EquipmentFuelConsumption::with(['equipment', 'user'])
            ->whereBetween('consumption_date', [$startDate, $endDate])
            ->where('approval_status', 'approved')
            ->get()
            ->map(function ($consumption) {
                return [
                    'id' => $consumption->id,
                    'equipment_name' => $consumption->equipment->name,
                    'fuel_type' => $consumption->fuel_type_text,
                    'quantity' => $consumption->quantity,
                    'consumption_date' => $consumption->consumption_date,
                    'date_formatted' => $consumption->consumption_date?->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
                    'status' => $consumption->approval_status_text,
                    'status_color' => $consumption->approval_status_color,
                    'user_name' => $consumption->user->name,
                    'notes' => $consumption->notes
                ];
            });

        // Calculate summary - based on approved records only
        $totalConsumption = $approvedConsumptions->sum('quantity');
        $byFuelType = $approvedConsumptions->groupBy('fuel_type')->map(function ($items) {
            return $items->sum('quantity');
        });

        return view('fuel-management.consumption-report', compact('consumptions', 'totalConsumption', 'byFuelType', 'startDate', 'endDate'));
    }

    /**
     * Print fuel consumption report
     */
    public function printConsumptionReport(Request $request)
    {
        try {
            $startDate = $request->has('start_date')
                ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay()
                : now()->startOfMonth();
            $endDate = $request->has('end_date')
                ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()
                : now()->endOfMonth();

            Log::info("Print Report Date Range: $startDate to $endDate");

            // Get all consumption records for display in table (both approved and rejected)
            $consumptions = EquipmentFuelConsumption::with(['equipment', 'user'])
                ->whereBetween('consumption_date', [$startDate, $endDate])
                ->orderBy('consumption_date', 'desc')
                ->get()
                ->map(function ($consumption) {
                    // Find the source fuel truck using fuel_truck_id first (new approach), then fallback to old logic
                    $fuelTruckName = '-';

                    // First, try to use the fuel_truck_id directly from consumption record
                    if ($consumption->fuel_truck_id) {
                        $fuelTruckEquipment = Equipment::find($consumption->fuel_truck_id);
                        if ($fuelTruckEquipment) {
                            $fuelTruckName = $fuelTruckEquipment->name;
                        }
                    }

                    // Fallback: Use old logic if fuel_truck_id is not set
                    if ($fuelTruckName === '-') {
                        if ($consumption->equipment->fuelTruck) {
                            // Equipment is itself a fuel truck
                            $fuelTruckName = $consumption->equipment->name;
                        } else {
                            // Equipment is not a fuel truck, find which fuel truck distributed to it
                            $distribution = \App\Models\FuelDistribution::where('target_equipment_id', $consumption->equipment->id)
                                ->whereIn('approval_status', ['approved', 'pending'])
                                ->latest('distribution_date')
                                ->first();

                            if ($distribution && $distribution->fuelTruck && $distribution->fuelTruck->equipment) {
                                $fuelTruckName = $distribution->fuelTruck->equipment->name;
                            }
                        }
                    }

                    return [
                        'id' => $consumption->id,
                        'equipment_name' => $consumption->equipment->name,
                        'fuel_truck_name' => $fuelTruckName,
                        'fuel_type' => $consumption->fuel_type_text,
                        'quantity' => $consumption->quantity,
                        'consumption_date' => $consumption->consumption_date,
                        'date_formatted' => $consumption->consumption_date?->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
                        'status' => $consumption->approval_status_text,
                        'status_color' => $consumption->approval_status_color,
                        'user_name' => $consumption->user->name,
                        'notes' => $consumption->notes,
                        'approval_status' => $consumption->approval_status
                    ];
                })->toArray();

            // Get only approved records for calculations
            $approvedRecords = EquipmentFuelConsumption::with(['equipment', 'user'])
                ->whereBetween('consumption_date', [$startDate, $endDate])
                ->where('approval_status', 'approved')
                ->get();

            $totalConsumption = $approvedRecords->sum('quantity');

            // Group by fuel_type text
            $byFuelType = $approvedRecords->groupBy(function ($consumption) {
                return $consumption->fuel_type_text;
            })->map(function ($items) {
                return $items->sum('quantity');
            })->toArray();

            Log::info("Total Consumptions: " . count($consumptions) . ", Total: " . $totalConsumption . ", By Type: " . json_encode($byFuelType));

            return view('fuel-management.consumption-report-print', compact('consumptions', 'totalConsumption', 'byFuelType', 'startDate', 'endDate'));
        } catch (\Exception $e) {
            Log::error('Print Consumption Report Error: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString());
            return redirect()->route('fuel-management.consumption-report')->with('error', 'حدث خطأ في الطباعة: ' . $e->getMessage());
        }
    }
}
