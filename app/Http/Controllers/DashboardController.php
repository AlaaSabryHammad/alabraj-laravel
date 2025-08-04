<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Transport;
use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_employees' => Employee::count(),
            'available_equipment' => Equipment::where('status', 'available')->count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'daily_trips' => Transport::whereDate('created_at', today())->count(),
            'new_documents' => Document::whereMonth('created_at', now()->month)->count(),
        ];

        // Get chart data (last 6 months)
        $chartData = $this->getChartData();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('dashboard.index', compact('stats', 'chartData', 'recentActivities'));
    }

    private function getMonthlyRevenue()
    {
        // This would typically come from a Finance/Invoice model
        // For now, return a placeholder value
        return 2400000;
    }

    private function getChartData()
    {
        // Get last 6 months financial data
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
        $chartData = [];

        foreach ($months as $month) {
            $chartData[] = [
                'name' => $month,
                'revenue' => rand(2000000, 3500000),
                'expenses' => rand(1500000, 2500000)
            ];
        }

        return $chartData;
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Get recent employee additions
        $recentEmployees = Employee::latest()->take(3)->get();
        foreach ($recentEmployees as $employee) {
            $activities[] = [
                'type' => 'employee',
                'message' => "تم إضافة موظف جديد: {$employee->name}",
                'time' => $employee->created_at->diffForHumans(),
                'icon' => 'ri-user-add-line',
                'color' => 'text-blue-600'
            ];
        }

        // Get recent projects
        $recentProjects = Project::latest()->take(2)->get();
        foreach ($recentProjects as $project) {
            $activities[] = [
                'type' => 'project',
                'message' => "مشروع جديد: {$project->name}",
                'time' => $project->created_at->diffForHumans(),
                'icon' => 'ri-building-line',
                'color' => 'text-green-600'
            ];
        }

        return collect($activities)->sortByDesc('created_at')->take(10);
    }
}
