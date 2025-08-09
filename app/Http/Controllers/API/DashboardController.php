<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'projects' => [
                    'total' => Project::count(),
                    'active' => Project::where('status', 'active')->count(),
                    'completed' => Project::where('status', 'completed')->count(),
                    'planning' => Project::where('status', 'planning')->count(),
                ],
                'employees' => [
                    'total' => Employee::count(),
                    'active' => Employee::where('status', 'active')->count(),
                    'present_today' => Attendance::whereDate('date', Carbon::today())
                        ->whereNotNull('check_in')
                        ->count(),
                ],
                'equipment' => [
                    'total' => Equipment::count(),
                    'active' => Equipment::where('status', 'active')->count(),
                    'maintenance' => Equipment::where('status', 'maintenance')->count(),
                    'damaged' => Equipment::where('status', 'damaged')->count(),
                ],
                'attendance' => [
                    'today_present' => Attendance::whereDate('date', Carbon::today())
                        ->whereNotNull('check_in')
                        ->count(),
                    'today_absent' => Employee::where('status', 'active')->count() -
                        Attendance::whereDate('date', Carbon::today())
                        ->whereNotNull('check_in')
                        ->count(),
                    'this_week' => Attendance::whereBetween('date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ])->count(),
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activities
     */
    public function recentActivities(): JsonResponse
    {
        try {
            $activities = [];

            // Recent project updates
            $recentProjects = Project::select('id', 'name', 'status', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'project',
                        'message' => "Project '{$project->name}' was updated",
                        'date' => $project->updated_at,
                        'data' => $project
                    ];
                });

            // Recent employee additions
            $recentEmployees = Employee::select('id', 'name', 'position', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($employee) {
                    return [
                        'type' => 'employee',
                        'message' => "New employee '{$employee->name}' added",
                        'date' => $employee->created_at,
                        'data' => $employee
                    ];
                });

            // Recent attendance records
            $recentAttendance = Attendance::with('employee:id,name')
                ->select('id', 'employee_id', 'date', 'check_in', 'check_out')
                ->whereDate('date', '>=', Carbon::now()->subDays(7))
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($attendance) {
                    return [
                        'type' => 'attendance',
                        'message' => "Attendance recorded for {$attendance->employee->name}",
                        'date' => $attendance->date,
                        'data' => $attendance
                    ];
                });

            // Combine all activities
            $activities = collect()
                ->concat($recentProjects)
                ->concat($recentEmployees)
                ->concat($recentAttendance)
                ->sortByDesc('date')
                ->take(10)
                ->values();

            return response()->json([
                'status' => 'success',
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch recent activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project progress chart data
     */
    public function projectProgress(): JsonResponse
    {
        try {
            $progressData = Project::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status => $item->count];
                });

            return response()->json([
                'status' => 'success',
                'data' => $progressData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch project progress data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance trends
     */
    public function attendanceTrends(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($days);

            $trends = Attendance::select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(*) as present_count')
            )
                ->where('date', '>=', $startDate)
                ->whereNotNull('check_in')
                ->groupBy(DB::raw('DATE(date)'))
                ->orderBy('date')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $trends
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch attendance trends',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
