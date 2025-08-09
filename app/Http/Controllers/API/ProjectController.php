<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $projects = Project::with(['employees', 'equipment'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $projects,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|in:planning,active,completed,suspended',
                'budget' => 'nullable|numeric|min:0',
                'location_id' => 'nullable|exists:locations,id',
            ]);

            $project = Project::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully',
                'data' => $project->load(['employees', 'equipment'])
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
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified project.
     */
    public function show($id): JsonResponse
    {
        try {
            $project = Project::with(['employees', 'equipment', 'materials'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'sometimes|required|in:planning,active,completed,suspended',
                'budget' => 'nullable|numeric|min:0',
                'location_id' => 'nullable|exists:locations,id',
            ]);

            $project->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated successfully',
                'data' => $project->load(['employees', 'equipment'])
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
                'message' => 'Failed to update project'
            ], 500);
        }
    }

    /**
     * Remove the specified project.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project'
            ], 500);
        }
    }

    /**
     * Get project employees
     */
    public function employees($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $employees = $project->employees()->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }
    }

    /**
     * Get project equipment
     */
    public function equipment($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $equipment = $project->equipment()->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $equipment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }
    }

    /**
     * Get project materials
     */
    public function materials($id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $materials = $project->materials()->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $materials
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found'
            ], 404);
        }
    }
}
