<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $sort = $request->get('sort', 'name');
            $direction = $request->get('direction', 'asc');

            $query = Employee::query();

            // Handle search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_number', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('position', 'LIKE', "%{$search}%")
                        ->orWhere('department', 'LIKE', "%{$search}%");
                });
            }

            // Handle sorting
            $allowedSorts = ['name', 'employee_number', 'email', 'position', 'hire_date', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy($sort, $direction);
            }

            $employees = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $employees,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'phone' => 'required|string|max:20',
                'department_id' => 'required|exists:departments,id',
                'position' => 'required|string|max:255',
                'hire_date' => 'required|date',
                'salary' => 'nullable|numeric|min:0',
                'employee_id' => 'required|string|unique:employees,employee_id',
                'national_id' => 'required|string|unique:employees,national_id',
                'passport_number' => 'nullable|string',
                'iqama_number' => 'nullable|string',
                'status' => 'required|in:active,inactive,terminated',
            ]);

            $employee = Employee::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'data' => $employee->load(['department', 'projects'])
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
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified employee.
     */
    public function show($id): JsonResponse
    {
        try {
            $employee = Employee::with(['department', 'projects', 'attendances'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:employees,email,' . $id,
                'phone' => 'sometimes|required|string|max:20',
                'department_id' => 'sometimes|required|exists:departments,id',
                'position' => 'sometimes|required|string|max:255',
                'hire_date' => 'sometimes|required|date',
                'salary' => 'nullable|numeric|min:0',
                'employee_id' => 'sometimes|required|string|unique:employees,employee_id,' . $id,
                'national_id' => 'sometimes|required|string|unique:employees,national_id,' . $id,
                'passport_number' => 'nullable|string',
                'iqama_number' => 'nullable|string',
                'status' => 'sometimes|required|in:active,inactive,terminated',
            ]);

            $employee->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
                'data' => $employee->load(['department', 'projects'])
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
                'message' => 'Failed to update employee'
            ], 500);
        }
    }

    /**
     * Remove the specified employee.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete employee'
            ], 500);
        }
    }

    /**
     * Get employee projects
     */
    public function projects($id): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);
            $projects = $employee->projects()->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }
    }

    /**
     * Get employee attendance
     */
    public function attendance($id, Request $request): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);

            $query = Attendance::where('employee_id', $id);

            // Filter by date range if provided
            if ($request->has('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }
            if ($request->has('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }

            $attendance = $query->orderBy('date', 'desc')->paginate(15);

            return response()->json([
                'status' => 'success',
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }
    }

    /**
     * Record employee attendance
     */
    public function recordAttendance($id, Request $request): JsonResponse
    {
        try {
            $employee = Employee::findOrFail($id);

            $validated = $request->validate([
                'date' => 'required|date',
                'check_in' => 'required|date_format:H:i',
                'check_out' => 'nullable|date_format:H:i|after:check_in',
                'break_start' => 'nullable|date_format:H:i',
                'break_end' => 'nullable|date_format:H:i|after:break_start',
                'notes' => 'nullable|string',
            ]);

            $validated['employee_id'] = $id;

            // Check if attendance already exists for this date
            $existing = Attendance::where('employee_id', $id)
                ->where('date', $validated['date'])
                ->first();

            if ($existing) {
                $existing->update($validated);
                $attendance = $existing;
            } else {
                $attendance = Attendance::create($validated);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance recorded successfully',
                'data' => $attendance
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
                'message' => 'Failed to record attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
