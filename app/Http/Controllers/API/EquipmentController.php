<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the equipment.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $equipment = Equipment::with(['project', 'category'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $equipment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch equipment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'equipment_id' => 'required|string|unique:equipment,equipment_id',
                'category' => 'required|string|max:255',
                'model' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_expiry' => 'nullable|date',
                'status' => 'required|in:active,maintenance,retired,damaged',
                'location' => 'nullable|string|max:255',
                'project_id' => 'nullable|exists:projects,id',
            ]);

            $equipment = Equipment::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Equipment created successfully',
                'data' => $equipment->load(['project'])
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create equipment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified equipment.
     */
    public function show($id): JsonResponse
    {
        try {
            $equipment = Equipment::with(['project', 'maintenances'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $equipment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipment not found'
            ], 404);
        }
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'equipment_id' => 'sometimes|required|string|unique:equipment,equipment_id,' . $id,
                'category' => 'sometimes|required|string|max:255',
                'model' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_expiry' => 'nullable|date',
                'status' => 'sometimes|required|in:active,maintenance,retired,damaged',
                'location' => 'nullable|string|max:255',
                'project_id' => 'nullable|exists:projects,id',
            ]);

            $equipment->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Equipment updated successfully',
                'data' => $equipment->load(['project'])
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update equipment'
            ], 500);
        }
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Equipment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete equipment'
            ], 500);
        }
    }

    /**
     * Get equipment maintenance history
     */
    public function maintenanceHistory($id): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);
            $maintenances = Maintenance::where('equipment_id', $id)
                ->orderBy('date', 'desc')
                ->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $maintenances
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Equipment not found'
            ], 404);
        }
    }

    /**
     * Add maintenance record
     */
    public function addMaintenance($id, Request $request): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);

            $validated = $request->validate([
                'date' => 'required|date',
                'type' => 'required|in:preventive,corrective,emergency',
                'description' => 'required|string',
                'cost' => 'nullable|numeric|min:0',
                'technician' => 'nullable|string|max:255',
                'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            ]);

            $validated['equipment_id'] = $id;
            $maintenance = Maintenance::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Maintenance record added successfully',
                'data' => $maintenance
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add maintenance record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update equipment status
     */
    public function updateStatus($id, Request $request): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:active,maintenance,retired,damaged',
            ]);

            $equipment->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Equipment status updated successfully',
                'data' => $equipment
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update equipment status'
            ], 500);
        }
    }
}
