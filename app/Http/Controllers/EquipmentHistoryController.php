<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\Location;
use App\Models\EquipmentDriverHistory;
use App\Models\EquipmentMovementHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipmentHistoryController extends Controller
{
    /**
     * Change equipment driver
     */
    public function changeDriver(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'new_driver_id' => 'nullable|exists:employees,id',
            'assigned_at' => 'required|date',
            'assignment_notes' => 'nullable|string|max:1000',
            'release_notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($equipment, $validated) {
            // Store old driver_id for status update logic
            $oldDriverId = $equipment->driver_id;

            // Complete current driver assignment if exists
            $currentAssignment = $equipment->currentDriverAssignment;
            if ($currentAssignment) {
                $currentAssignment->update([
                    'released_at' => now(),
                    'release_notes' => $validated['release_notes'] ?? 'تم تغيير السائق',
                    'status' => 'completed'
                ]);
            }

            // Create new driver assignment if driver selected
            if ($validated['new_driver_id']) {
                EquipmentDriverHistory::create([
                    'equipment_id' => $equipment->id,
                    'driver_id' => $validated['new_driver_id'],
                    'assigned_by' => Auth::id(),
                    'assigned_at' => $validated['assigned_at'],
                    'assignment_notes' => $validated['assignment_notes'],
                    'status' => 'active'
                ]);

                // Update equipment current driver
                $equipment->update(['driver_id' => $validated['new_driver_id']]);
            } else {
                // Remove driver from equipment
                $equipment->update(['driver_id' => null]);
            }

            // Auto-update status based on driver assignment
            $this->updateEquipmentStatusBasedOnDriver($equipment, $oldDriverId, $validated['new_driver_id'] ?? null);
        });

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'تم تحديث السائق بنجاح');
    }

    /**
     * Move equipment to different location
     */
    public function moveEquipment(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'new_location_id' => 'nullable|exists:locations,id',
            'moved_at' => 'required|date',
            'movement_reason' => 'nullable|string|max:1000',
            'movement_type' => 'required|in:location_change,maintenance,disposal,other',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($equipment, $validated) {
            // Get the new location name if location_id is provided
            $newLocationText = null;
            if ($validated['new_location_id']) {
                $location = Location::find($validated['new_location_id']);
                $newLocationText = $location ? $location->name : null;
            }

            // Record movement history
            EquipmentMovementHistory::create([
                'equipment_id' => $equipment->id,
                'from_location_id' => $equipment->location_id,
                'from_location_text' => $equipment->location,
                'to_location_id' => $validated['new_location_id'],
                'to_location_text' => $newLocationText,
                'moved_by' => Auth::id(),
                'moved_at' => $validated['moved_at'],
                'movement_reason' => $validated['movement_reason'],
                'movement_type' => $validated['movement_type'],
                'notes' => $validated['notes']
            ]);

            // Update equipment location
            $equipment->update([
                'location_id' => $validated['new_location_id'],
                'location' => $newLocationText
            ]);
        });

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'تم نقل المعدة بنجاح');
    }

    /**
     * Get driver history for equipment
     */
    public function getDriverHistory(Equipment $equipment)
    {
        $history = $equipment->driverHistory()
            ->with(['driver', 'assignedBy'])
            ->paginate(10);

        return response()->json($history);
    }

    /**
     * Get movement history for equipment
     */
    public function getMovementHistory(Equipment $equipment)
    {
        $history = $equipment->movementHistory()
            ->with(['fromLocation', 'toLocation', 'movedBy'])
            ->orderBy('moved_at', 'desc')
            ->paginate(10);

        // Transform the data to include location names
        $history->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'equipment_id' => $item->equipment_id,
                'from_location_id' => $item->from_location_id,
                'from_location_text' => $item->from_location_text,
                'from_location_name' => $item->fromLocation ? $item->fromLocation->name : ($item->from_location_text ?? 'غير محدد'),
                'to_location_id' => $item->to_location_id,
                'to_location_text' => $item->to_location_text,
                'to_location_name' => $item->toLocation ? $item->toLocation->name : ($item->to_location_text ?? 'غير محدد'),
                'moved_by' => $item->moved_by,
                'moved_by_name' => $item->movedBy ? $item->movedBy->name : 'غير معروف',
                'moved_at' => $item->moved_at,
                'movement_reason' => $item->movement_reason,
                'movement_type' => $item->movement_type,
                'notes' => $item->notes,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return response()->json($history);
    }

    /**
     * Update equipment status based on driver assignment
     */
    private function updateEquipmentStatusBasedOnDriver(Equipment $equipment, $oldDriverId, $newDriverId)
    {
        // If driver was assigned (from null or different driver)
        if (!$oldDriverId && $newDriverId) {
            // Driver assigned for the first time
            if ($equipment->status === 'available') {
                $equipment->update(['status' => 'in_use']);
                Log::info('Equipment status changed to in_use via driver change', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'driver_id' => $newDriverId,
                    'reason' => 'Driver assigned via changeDriver'
                ]);
            }
        }
        // If driver was removed
        elseif ($oldDriverId && !$newDriverId) {
            // Driver removed
            if ($equipment->status === 'in_use') {
                $equipment->update(['status' => 'available']);
                Log::info('Equipment status changed to available via driver change', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'old_driver_id' => $oldDriverId,
                    'reason' => 'Driver removed via changeDriver'
                ]);
            }
        }
        // If driver was changed (from one driver to another)
        elseif ($oldDriverId && $newDriverId && $oldDriverId !== $newDriverId) {
            // Driver changed - keep status as in_use if it was already in_use
            if ($equipment->status === 'available') {
                $equipment->update(['status' => 'in_use']);
                Log::info('Equipment status changed to in_use via driver change', [
                    'equipment_id' => $equipment->id,
                    'equipment_name' => $equipment->name,
                    'old_driver_id' => $oldDriverId,
                    'new_driver_id' => $newDriverId,
                    'reason' => 'Driver changed via changeDriver'
                ]);
            }
        }
    }
}
