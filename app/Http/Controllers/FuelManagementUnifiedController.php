<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\FuelTruck;
use App\Models\FuelDistribution;
use App\Models\EquipmentFuelConsumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    ->andWhere('type', 'NOT LIKE', '%محروقات%')
                    ->andWhere('type', 'NOT LIKE', '%وقود%');
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
        $truck = Equipment::with(['fuelTruck', 'location', 'driver'])
            ->findOrFail($truckId);

        $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
            ->where('fuel_truck_id', $truck->fuelTruck->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($dist) {
                return [
                    'id' => $dist->id,
                    'type' => 'distribution',
                    'equipment_name' => $dist->targetEquipment->name,
                    'quantity' => $dist->quantity,
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
                'capacity' => $truck->fuelTruck->capacity,
                'current_quantity' => $truck->fuelTruck->current_quantity,
                'remaining_quantity' => $truck->fuelTruck->remaining_quantity,
                'percentage' => $truck->fuelTruck->capacity > 0
                    ? ($truck->fuelTruck->remaining_quantity / $truck->fuelTruck->capacity) * 100
                    : 0
            ],
            'distributions' => $distributions
        ]);
    }

    /**
     * Get fuel consumption report
     */
    public function consumptionReport(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::createFromFormat('Y-m-d', $request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->has('end_date') ? Carbon::createFromFormat('Y-m-d', $request->get('end_date')) : now()->endOfMonth();

        // Get consumption records with grouping
        $consumptions = EquipmentFuelConsumption::with(['equipment', 'user'])
            ->whereBetween('consumption_date', [$startDate, $endDate])
            ->orderBy('consumption_date', 'desc')
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

        // Calculate summary
        $totalConsumption = $consumptions->sum('quantity');
        $byFuelType = $consumptions->groupBy('fuel_type')->map(function ($items) {
            return $items->sum('quantity');
        });

        return view('fuel-management.consumption-report', compact('consumptions', 'totalConsumption', 'byFuelType', 'startDate', 'endDate'));
    }
}
